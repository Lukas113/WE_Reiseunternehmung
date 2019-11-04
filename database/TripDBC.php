<?php

namespace database;

use entities\Trip;
use entities\TripTemplate;
use entities\Dayprogram;
use database\BusDBC;
use database\HotelDBC;
use database\InsuranceDBC;
use database\UserDBC;
use database\InvoiceDBC;

/**
 * Provides secure access to the database of {@link Trip}, {@link TripTemplate} and {@link Dayprogram} releated queries
 * <ul>
 * <li>{@link createTripTemplate($tripTemplate)}</li>
 * <li>{@link deleteTripTemplate($tripTemplate)}</li>
 * <li>{@link getAllTripTemplates()}</li>
 * <li>{@link getBookableTripTemplates()}</li>
 * <li>{@link findTripTemplateById($templateId, $close)}</li>
 * <li>{@link getDayprogramsFromTemplate($tripTemplate)}</li>
 * <li>{@link createDayprogram($dayprogram)}</li>
 * <li>{@link deleteDayprogram($dayprogram)}</li>
 * <li>{@link findDayprogramById($dayprogramId, $close)}</li>
 * <li>{@link changeBookable($tripTemplate)}</li>
 * <li>{@link createTrip($trip)}</li>
 * <li>{@link deleteTrip($trip)}</li>
 * <li>{@link getBookedTrips($userId)}</li>
 * <li>{@link findTripById($tripId, $shallow)}</li>
 * <li>{@link lockInvoicesRegistered($trip)}</li>
 * <li>{@link unlockInvoicesRegistered($trip)}</li>
 * </ul>
 * @author Lukas
 */
class TripDBC extends DBConnector {
    
    /**
     * Stores the {@link TripTemplate} into the database
     * @param TripTemplate $tripTemplate
     * @return boolean|int
     */
    public function createTripTemplate($tripTemplate){
        $stmt = $this->mysqliInstance->prepare("INSERT INTO triptemplate VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        if(!$stmt){
            return false;
        }
        $stmt->bind_param('ssiiidsii', $name, $description, $minAllocation, $maxAllocation, 
                $durationInDays, $price, $picturePath, $bookable, $fk_bus_id);
        $name = $tripTemplate->getName();
        $description = $tripTemplate->getDescription();
        $minAllocation = $tripTemplate->getMinAllocation();
        $maxAllocation = $tripTemplate->getMaxAllocation();
        $durationInDays = $tripTemplate->getDurationInDays();
        $price = $tripTemplate->getPrice();
        $picturePath = $tripTemplate->getPicturePath();
        $bookable = intval($tripTemplate->getBookable());//Converting boolean to int
        $fk_bus_id = $tripTemplate->getBus()->getId();
        return $this->executeInsert($stmt);
    }
    
    /**
     * Deletes the {@link TripTemplate} and all related {@link Dayprogram} from the database
     * @param TripTemplate $tripTemplate
     * @return boolean
     */
    public function deleteTripTemplate($tripTemplate){
        //Stores the picturePath from TripTemplate to remove later
        $tripTemplate = $this->findTripTemplateById($tripTemplate->getId());
        if($tripTemplate){
            $tripTemplatePicturePath = $tripTemplate->getPicturePath();
        }
        $dayporgramsPicturePaths = array();
        
        //Begins the transaction
        $this->mysqliInstance->begin_transaction();
        $this->mysqliInstance->autocommit(false);
        
        //Deletes the TripTemplate
        $stmt = $this->mysqliInstance->prepare("DELETE FROM triptemplate WHERE id = ?");
        if(!$stmt){
            // rollback if prep stat execution fails
            $this->mysqliInstance->rollback();
            exit();
        }
        $stmt->bind_param('i', $id);
        $id = $tripTemplate->getId();
        if(!$stmt->execute()){
            // rollback if prep stat execution fails
            $this->mysqliInstance->rollback();
            exit();
        }
        
        //Deletes the Dayprograms according to the TripTemplate
        $dayprograms = $this->getDayprogramsFromTemplate($tripTemplate);
        foreach($dayprograms as $dayprogram){
            array_push($dayporgramsPicturePaths, $dayprogram->getPicturePath());
            $stmt = $this->mysqliInstance->prepare("DELETE FROM dayprogram WHERE id = ?");
            if(!$stmt){
                // rollback if prep stat execution fails
                $this->mysqliInstance->rollback();
                exit();
            }
            $stmt->bind_param('i', $id);
            $id = $dayprogram->getId();
            if(!$stmt->execute()){
                // rollback if prep stat execution fails
                $this->mysqliInstance->rollback();
                exit();
            }
        }
        
        $success = $this->mysqliInstance->commit();
        $this->mysqliInstance->autocommit(true);
        $stmt->close();
        
        //deletes the pictures from the folder
        if($success){
            if(file_exists($tripTemplatePicturePath) and strpos($tripTemplatePicturePath, 'default') == false){
                unlink($tripTemplatePicturePath);
            }
            foreach($dayporgramsPicturePaths as $path){
                if(file_exists($path) and strpos($path, 'default') == false){
                    unlink($path);
                }
            }
        }
        return true;
    }
    
    /**
     * Gets all available {@link TripTemplate} from the database order by name asc
     * @return boolean|array
     */
    public function getAllTripTemplates(){
        $stmt = $this->mysqliInstance->prepare("SELECT * FROM triptemplate ORDER BY name ASC");
        if(!$stmt){
            return false;
        }
        $stmt->execute();
        $templates = array();
        $result = $stmt->get_result();
        while($tripTemplate = $result->fetch_object("entities\TripTemplate")){
            array_push($templates, $tripTemplate);
        }

        $stmt->close();
        
        $busDBC = new BusDBC();
        foreach($templates as $template){
            $template->setBus($busDBC->findBusById($template->getFkBusId()));
        }
        return $templates;
    }
    
    /**
     * Gets all {@link TripTemplate} which are bookable
     * @return boolean|array
     */
    public function getBookableTripTemplates(){
        $stmt = $this->mysqliInstance->prepare("SELECT * FROM triptemplate WHERE bookable = ? ORDER BY name ASC");
        if(!$stmt){
            return false;
        }
        $stmt->bind_param('i', $bookable);
        $bookable = intval(true); 
        $stmt->execute();
        $templates = array();
        $result = $stmt->get_result();
        while($tripTemplate = $result->fetch_object("entities\TripTemplate")){
            array_push($templates, $tripTemplate);
        }

        $stmt->close();
        $busDBC = new BusDBC();
        foreach($templates as $template){
            $template->setBus($busDBC->findBusById($template->getFkBusId()));
        }
        return $templates;
    }
    
    /**
     * Finds the {@link TripTemplate} and the according {@link Bus} by the given id from the database
     * @param int $templateId
     * @param boolean $close (false if closing of connection is NOT desired)
     * @return boolean|TripTemplate
     */
    public function findTripTemplateById($templateId, $close = true){
        $stmt = $this->mysqliInstance->prepare("SELECT * FROM triptemplate where id = ?");
        if(!$stmt){
            return false;
        }
        $stmt->bind_param('i', $id);
        $id = $templateId;
        $stmt->execute();
        $templateObj = $stmt->get_result()->fetch_object("entities\TripTemplate");
        
        //closing of the connection if desired
        if($close){
            $stmt->close();
        }
        
        if($templateObj){
            $busDBC = new BusDBC();
            $bus = $busDBC->findBusById($templateObj->getFkBusId());
            $templateObj->setBus($bus);
            return $templateObj;
        }else{
            return false;
        }
    }
    
    /**
     * Gets all {@link Dayprogram} from the database which belongs to the {@link TripTemplate}
     * @param TripTemplate $tripTemplate
     * @return boolean|array
     */
    public function getDayprogramsFromTemplate($tripTemplate){
        $stmt = $this->mysqliInstance->prepare("SELECT * FROM dayprogram WHERE fk_tripTemplate_id = ? ORDER BY dayNumber ASC");
        if(!$stmt){
            return false;
        }
        $stmt->bind_param('i', $fk_tripTemplate_id);
        $fk_tripTemplate_id = $tripTemplate->getId(); 
        $stmt->execute();
        $dayprograms = array();
        $result = $stmt->get_result();
        while($dayprogram = $result->fetch_object("entities\Dayprogram")){
            array_push($dayprograms, $dayprogram);
        }
        $stmt->close();
        
        //Adds the Hotels to the Dayprograms
        $hotelDBC = new HotelDBC();
        foreach($dayprograms as $daypgrm){
            $daypgrm->setHotel($hotelDBC->findHotelById($daypgrm->getFkHotelId()));
        }

        return $dayprograms;
    }
    
    /**
     * Stores the {@link Dayprogram} into the database to the according {@link TripTemplate}<br>
     * Updates automatically the price of the {@link TripTemplate} which belongs to the {@link Dayprogram}<br>
     * Ensures rollback of the transaction if any exception occures in the creation of a {@link Dayprogram}
     * @param Dayprogram $dayprogram
     * @return boolean
     */
    public function createDayprogram($dayprogram){
        $this->mysqliInstance->begin_transaction();
        $this->mysqliInstance->autocommit(false);
        try{
            $result = $this->createDayprogram2($dayprogram);
            $this->mysqliInstance->autocommit(true);
            return $result;
        } catch (Exception $ex) {
            $this->mysqliInstance->rollback();
            $this->mysqliInstance->autocommit(true);
            return false;
        }
    }

    /** 
     * Stores the {@link Dayprogram} into the database and updates the price and dutationInDays of the according {@link TripTemplate}
     * @param Dayprogram $dayprogram
     * @return boolean
     */
    private function createDayprogram2($dayprogram){
        //Insert of Dayprogram
        $stmt = $this->mysqliInstance->prepare("INSERT INTO dayprogram VALUES (NULL, ?, ?, ?, ?, ?, ?)");
        if(!$stmt){
            $this->mysqliInstance->rollback();
            exit();
        }
        $stmt->bind_param('ssisii', $name, $picturePath, $dayNumber, $description, 
                $fk_tripTemplate_id, $fk_hotel_id);
        $name = $dayprogram->getName();
        $picturePath = $dayprogram->getPicturePath();
        $dayNumber = $dayprogram->getDayNumber();
        $description = $dayprogram->getDescription();
        $fk_tripTemplate_id = $dayprogram->getFkTripTemplateId();
        $hotelId = $dayprogram->getFkHotelId();
        if($hotelId === 0){
            $fk_hotel_id = null;
        }else{
            $fk_hotel_id = $hotelId;
        }
        if(!$stmt->execute()){
            $this->mysqliInstance->rollback();
            exit();
        }

        //Gets the TripTemplate to update
        $tripTemplate = $this->findTripTemplateById($fk_tripTemplate_id, false);
        if(!$tripTemplate){
            $this->mysqliInstance->rollback();
            exit();
        }

        //Gets the Bus of the TripTemplate to calculate with pricePerDay
        $busDBC = new BusDBC();
        $bus = $busDBC->findBusById($tripTemplate->getFkBusId(), false);
        if(!$bus){
            $this->mysqliInstance->rollback();
            exit();
        }

        //Gets the Hotel of the Dayprogram to calculate with pricePerPerson
        $hotelDBC = new HotelDBC();
        $hotel = $hotelDBC->findHotelById($dayprogram->getFkHotelId(), false);
        if(!$hotel and $fk_hotel_id != null){
            $this->mysqliInstance->rollback();
            exit();
        }

        //updates the price and durationInDays of the TripTemplate
        $stmt = $this->mysqliInstance->prepare("UPDATE triptemplate SET price = ?, durationInDays = ? WHERE id = ?");
        if(!$stmt){
            $this->mysqliInstance->rollback();
            exit();
        }
        $stmt->bind_param('dii', $price, $durationInDays, $tripTemplateId);
        //Calculates and adds the minPrice for the TripTemplate
        if($fk_hotel_id === null or $hotel === false){
            $hotelPricePerPerson = 0;
        }else{
            $hotelPricePerPerson = $hotel->getPricePerPerson();
        }
        $price = $tripTemplate->getPrice() + $bus->getPricePerDay() + $tripTemplate->getMinAllocation() * $hotelPricePerPerson;
        $durationInDays = $tripTemplate->getDurationInDays() + 1;
        $tripTemplateId = $tripTemplate->getId();
        if(!$stmt->execute()){
            $this->mysqliInstance->rollback();
            exit();
        }
        
        $this->mysqliInstance->commit();
        $stmt->close();
        return true;
    }
    
    /**
     * Deletes the {@link Dayprogram} from the database<br>
     * Updates automatically the price of the {@link TripTemplate} which belongs to the {@link Dayprogram}<br>
     * Ensures rollback of the transaction if any exception occures in the deletion of a {@link Dayprogram}
     * @param Dayprogram $dayprogram
     * @return boolean
     */
    public function deleteDayprogram($dayprogram){
        $this->mysqliInstance->begin_transaction();
        $this->mysqliInstance->autocommit(false);
        try{
            $result = $this->deleteDayprogram2($dayprogram);
            $this->mysqliInstance->autocommit(true);
            return $result;
        } catch (Exception $ex) {
            $this->mysqliInstance->rollback();
            $this->mysqliInstance->autocommit(true);
            return false;
        }
    }
    
    /** 
     * Deletes the {@link Dayprogram} from the database and updates the price and dutationInDays of the according {@link TripTemplate}
     * @param Dayprogram $dayprogram
     * @return boolean
     */
    private function deleteDayprogram2($dayprogram){
        //Gets the real object of the Dayprogram
        $dayprogram = $this->findDayprogramById($dayprogram->getId(), false);
        if($dayprogram){
            $picturePath = $dayprogram->getPicturePath();
        }
        
        //Elimination of Dayprogram
        $stmt = $this->mysqliInstance->prepare("DELETE FROM dayprogram WHERE id = ?");
        if(!$stmt){
            $this->mysqliInstance->rollback();
            exit();
        }
        $stmt->bind_param('i', $dayprogramId);
        $dayprogramId = $dayprogram->getId();
        if(!$stmt->execute()){
            $this->mysqliInstance->rollback();
            exit();
        }
        
        //Gets the TripTemplate to update
        $tripTemplate = $this->findTripTemplateById($dayprogram->getFkTripTemplateId(), false);
        if(!$tripTemplate){
            $this->mysqliInstance->rollback();
            exit();
        }
        
        //Gets the Bus of the TripTemplate to calculate with pricePerDay
        $busDBC = new BusDBC();
        $bus = $busDBC->findBusById($tripTemplate->getFkBusId(), false);
        if(!$bus){
            $this->mysqliInstance->rollback();
            exit();
        }
        
        //Gets the Hotel of the Dayprogram to calculate with pricePerPerson
        $hotelDBC = new HotelDBC();
        $hotel = $hotelDBC->findHotelById($dayprogram->getFkHotelId(), false);
        if($hotel){
            $hotelPricePerPerson = $hotel->getPricePerPerson();
        }else{
            $hotelPricePerPerson = 0;
        }
        
        //updates the price and durationInDays of the TripTemplate
        $stmt = $this->mysqliInstance->prepare("UPDATE triptemplate SET price = ?, durationInDays = ? WHERE id = ?");
        if(!$stmt){
            $this->mysqliInstance->rollback();
            exit();
        }
        $stmt->bind_param('dii', $price, $durationInDays, $tripTemplateId);
        $durationInDays = $tripTemplate->getDurationInDays() - 1;
        //Calculates and decreases the minPrice for the TripTemplate
        if($durationInDays <= 0){
            $price = 0;
        }else{
            $price = $tripTemplate->getPrice() - $bus->getPricePerDay() - ($tripTemplate->getMinAllocation() * $hotelPricePerPerson);
        }
        $tripTemplateId = $tripTemplate->getId();
        if(!$stmt->execute()){
            $this->mysqliInstance->rollback();
            exit();
        }
        
        $result = $this->mysqliInstance->commit();
        $stmt->close();
        if($result){
            if(file_exists($picturePath) and strpos($picturePath, 'default') == false){
                unlink($picturePath);
            }
        }
        return true;
    }
    
    /**
     * Finds the {@link Dayprogram} by the given id from the database
     * @param int $templateId, $close (false if closing of connection is NOT desired)
     * @return boolean|Dayprogram
     */
    public function findDayprogramById($dayprogramId, $close = true){
        $stmt = $this->mysqliInstance->prepare("SELECT * FROM dayprogram where id = ?;");
        if(!$stmt){
            return false;
        }
        $stmt->bind_param('i', $id);
        $id = $dayprogramId;
        $stmt->execute();
        $dayprogramObj = $stmt->get_result()->fetch_object("entities\Dayprogram");
        
        //closing of the connection if desired
        if($close){
            $stmt->close();
        }
        
        //checks whether the Dayprogram exists
        if($dayprogramObj){
            return $dayprogramObj;
        }else{
            return false;
        }
    }
    
    /**
     * Changes the bookability of the given {@link TripTemplate} to get the {@link User} the access to these {@link TripTemplate}
     * @param type $tripTemplate
     * @return boolean
     */
    public function changeBookable($tripTemplate){
        //Gets the object of the TripTemplate
        $tripTemplateObj = $this->findTripTemplateById($tripTemplate->getId());
        if(!$tripTemplateObj){
            return false;
        }
        
        //Locks or unlocks the bookable
        $stmt = $this->mysqliInstance->prepare("UPDATE triptemplate SET bookable = ? WHERE id = ?");
        $stmt->bind_param('ii', $bookable, $id);
        $id = $tripTemplateObj->getId();
        if($tripTemplateObj->getBookable()){
            //Locks bookable
            $bookable = intval(false);
        }else{
            //Unlocks bookable
            $bookable = intval(true);
        }
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
    
    /**
     * Stores the booked {@link Trip} into the database.
     * Ensures rollback of the transaction if any exception occures in the creation of the {@link Trip}
     * @param Trip $trip
     * @return boolean
     */
    public function createTrip($trip){
        $this->mysqliInstance->begin_transaction();
        $this->mysqliInstance->autocommit(false);
        try{
            $result = $this->createTrip2($trip);
            $this->mysqliInstance->autocommit(true);
            return $result;
        } catch (Exception $ex) {
            $this->mysqliInstance->rollback();
            $this->mysqliInstance->autocommit(true);
            return false;
        }
    }
    
    /**
     * Stores the {@link Trip} and the {@link Participants} into the database<br>
     * Computes the total internal price of the {@link Trip}<br>
     * Ensures rollback of the transaction if any data or query is corrupt
     * @param Trip $trip
     * @return boolean
     */
    private function createTrip2($trip){
        //Ensures that the User doesn't book several trips in 30 sec
        $userDBC = new UserDBC();
        $user = $userDBC->findUserById($_SESSION['userId']);
        if($user->getLastBooking() != null and $user->getLastBooking() + 30 > \time()){
            return false;
        }
        
        //Gets the TripTemplate
        $tripTemplate = $this->findTripTemplateById($trip->getFkTripTemplateId(), false);
        if(!$tripTemplate){
            return false;
        }
        
        //Adds the Dayprograms to the TripTemplate
        $dayprograms = $this->getDayprogramsFromTemplate($tripTemplate);
        if(!$dayprograms){
            return false;
        }
        $tripTemplate->setDayprograms($dayprograms);
        
        //Gets the Insurance if chosen
        $insurance = null;
        if($trip->getFkInsuranceId() > 0){
            $insuranceDBC = new InsuranceDBC();
            $insurance = $insuranceDBC->findInsuranceById($trip->getFkInsuranceId());
            if(!$insurance){
                return false;
            }
        }
        
        //Insert of Trip
        $stmt = $this->mysqliInstance->prepare("INSERT INTO trip VALUES (NULL, ?, ?, ?, ?, NULL, ?, ?, ?)");
        if(!$stmt){
            $this->mysqliInstance->rollback();
            exit();
        }
        $stmt->bind_param('idssiii', $numOfParticipation, $price, $departureDate, $bookingDate, 
                $fk_user_id, $fk_insurance_id, $fk_tripTemplate_id);
        $numOfParticipation = $trip->getNumOfParticipation();
        
        //Calculates the price
        $price = $tripTemplate->getHotelPricePerPerson() * $numOfParticipation + $tripTemplate->getBusPrice();
        if($insurance){
            $price += $insurance->getPricePerPerson() * $numOfParticipation;
        }
        
        $departureDate = $trip->getDepartureDate();
        $bookingDate = \date("Y-m-d");
        $fk_user_id = $trip->getFkUserId();
        $fk_insurance_id = $trip->getFkInsuranceId();
        $fk_tripTemplate_id = $trip->getFkTripTemplateId();
        if(!$stmt->execute()){
            $this->mysqliInstance->rollback();
            exit();
        }
        $tripId = $stmt->insert_id;
        
        //Storage of Participants
        $participantIds = $trip->getParticipantIds();
        foreach($participantIds as $participantId){
            $stmt = $this->mysqliInstance->prepare("INSERT INTO tripparticipant VALUES (?, ?)");
            if(!$stmt){
                $this->mysqliInstance->rollback();
                exit();
            }
            $stmt->bind_param('ii', $fk_trip_id, $fk_participant_id);
            $fk_trip_id = $tripId;
            $fk_participant_id = $participantId;
            if(!$stmt->execute()){
                $this->mysqliInstance->rollback();
                exit();
            }
        }
        
        $success = $this->mysqliInstance->commit();
        $stmt->close();
        
        //Updates the timestamp on the User
        if($success){
            $success = $tripId;
            $userDBC->setTimeStamp();
        }
        return $success;
    }
    
    /**
     * Deletes the {@link Trip} from the database<br>
     * Ensures rollback of the transaction if any exception occures in the deletion of a {@link Trip}
     * @param type $trip
     * @return boolean
     */
    public function deleteTrip($trip){
        $this->mysqliInstance->begin_transaction();
        $this->mysqliInstance->autocommit(false);
        try{
            $result = $this->deleteTrip2($trip);
            $this->mysqliInstance->autocommit(true);
            return $result;
        } catch (Exception $ex) {
            $this->mysqliInstance->rollback();
            $this->mysqliInstance->autocommit(true);
            return false;
        }
    }
    
    /**
     * Deletes the {@link Trip and the according {@link Participant} from the Trip-booking<br>
     * Ensures rollback of the transaction if there is any corrupt data
     * @param Trip $trip
     * @return boolean
     */
    private function deleteTrip2($trip){
        //Deletes the Trip
        $stmt = $this->mysqliInstance->prepare("DELETE FROM trip WHERE id = ?");
        if(!$stmt){
            $this->mysqliInstance->rollback();
            exit();
        }
        $stmt->bind_param('i', $tripId);
        $tripId = $trip->getId();
        if(!$stmt->execute()){
            $this->mysqliInstance->rollback();
            exit();
        }
        
        //Deletes all Participants from the Trip
        $stmt = $this->mysqliInstance->prepare("DELETE FROM tripparticipant WHERE fk_trip_id = ?");
        if(!$stmt){
            $this->mysqliInstance->rollback();
            exit();
        }
        $stmt->bind_param('i', $tripId);
        $tripId = $trip->getId();
        if(!$stmt->execute()){
            $this->mysqliInstance->rollback();
            exit();
        }
        
        $this->mysqliInstance->commit();
        $stmt->close();
        return true;
    }
    
    /**
     * Gets the booked {@link Trip} to get an overview of them
     * @param int $userId (if request is restricted to a single User)
     * @return boolean|array
     */
    public function getBookedTrips($userId = null){
        //Gets all the Trips from the database
        $stmt;
        if(!$userId){
            //Admins query
            $stmt = $this->mysqliInstance->prepare("SELECT * FROM trip ORDER BY departureDate DESC");
        }else{
            //Users query
            $stmt = $this->mysqliInstance->prepare("SELECT * FROM trip WHERE fk_user_id = ? ORDER BY departureDate DESC");
            $stmt->bind_param('i', $fk_user_id);
            $fk_user_id = $userId;
        }
        if(!$stmt){
            return false;
        }
        $stmt->execute();
        $trips = array();
        $result = $stmt->get_result();
        $userDBC = new UserDBC();
        while($trip = $result->fetch_object("entities\Trip")){
            $trip->setUser($userDBC->findUserById($trip->getFkUserId()));
            array_push($trips, $trip);
        }
        $stmt->close();
        if(sizeof($trips) < 1){
            return $trips;
        }
        
        
        
        //Adds the TripTemplates and the Insurance to the Trips
        $insuranceDBC = new InsuranceDBC();
        foreach($trips as $trip){
            $trip->setTripTemplate($this->findTripTemplateById($trip->getFkTripTemplateId()));
            $insurance = $insuranceDBC->findInsuranceById($trip->getFkInsuranceId());
            $trip->setInsurance($insurance);
        }
        
        return $trips;
    }
    
    /**
     * Finds the {@link Trip} and all {@link User}, {@link Participant}, {@link Insurance}, {@link Invoices}, 
     * {@link TripTemplate}, {@link Bus}, {@link Dayprograms}, {@link Hotels} data according to the {@link Trip}
     * @param int $tripId
     * @return boolean|Trip
     */
    public function findTripById($tripId, $shallow = false){
        //Gets the Trip object from the database
        $stmt = $this->mysqliInstance->prepare("SELECT * FROM trip where id = ?;");
        if(!$stmt){
            return false;
        }
        $stmt->bind_param('i', $id);
        $id = $tripId;
        $stmt->execute();
        $tripObj = $stmt->get_result()->fetch_object("entities\Trip");
        if(!$tripObj){
            $stmt->close();
            return false;
        }
        
        //Adds the Insurance to the Trip
        $insuranceDBC = new InsuranceDBC();
        $insurance = $insuranceDBC->findInsuranceById($tripObj->getFkInsuranceId());
        $tripObj->setInsurance($insurance);
        
        //Adds the TripTemplate, Bus, Dayprograms, Hotel to the Trip
        $tripTemplate = $this->findTripTemplateById($tripObj->getFkTripTemplateId());
        if($tripTemplate and !$shallow){
            $tripTemplate->setDayprograms($this->getDayprogramsFromTemplate($tripTemplate));
        }
        $tripObj->setTripTemplate($tripTemplate);
        
        //If it is just a shallow query, then it returns just a Part of the entity Trip some unnecessary deep objects
        if($shallow){
            $stmt->close();
            return $tripObj;
        }

        //Adds the Participants to the Trip
        $userDBC = new UserDBC();
        $participants = $userDBC->findParticipantsToTrip($tripId);
        $tripObj->setParticipants($participants);
        
        //Adds the User to the Trip
        $user = $userDBC->findUserById($tripObj->getFkUserId());
        $tripObj->setUser($user);
        
        //Adds the invoices to the Trip
        $invoiceDBC = new InvoiceDBC();
        $invoices = $invoiceDBC->findTripInvoices($tripId);
        $tripObj->setInvoices($invoices);

        return $tripObj;
        
    }
    
    /**
     * Locks InvoicesRegistered in the {@link Trip} to prepare the cost-calculation
     * @param type $trip
     * @return boolean
     */
    public function lockInvoicesRegistered($trip){      
        $stmt = $this->mysqliInstance->prepare("UPDATE trip SET invoicesRegistered = ? WHERE id = ?");
        if(!$stmt){
            return false;
        }
        $stmt->bind_param('si', $invoicesRegistered, $id);
        $id = $trip->getId();
        $invoicesRegistered = date("Y-m-d");
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
    
    /**
     * Unocks InvoicesRegistered in the {@link Trip} to provide access to the upload if the locking of the
     * invoiceregistered was made too early
     * @param Trip $trip
     * @return boolean
     */
    public function unlockInvoicesRegistered($trip){
        $stmt = $this->mysqliInstance->prepare("UPDATE trip SET invoicesRegistered = NULL WHERE id = ?");
        if(!$stmt){
            return false;
        }
        $stmt->bind_param('i', $id);
        $id = $trip->getId();
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
    
}
