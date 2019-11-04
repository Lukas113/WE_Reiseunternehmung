<?php

namespace controllers;

use entities\Trip;
use entities\TripTemplate;
use entities\Dayprogram;
use entities\User;
use database\TripDBC;
use database\BusDBC;
use database\HotelDBC;
use database\InsuranceDBC;
use helpers\Validation;
use helpers\Upload;
use views\LayoutRendering;
use views\TemplateView;

/**
 * Controls the access to the functionalities of the {@link Trip}, {@link TripTemplate} and {@link Dayprogram}
 * <ul>
 * <li>{@link createTripTemplate()}</li>
 * <li>{@link getAllTrips()}</li>
 * <li>{@link getAllTripTemplates()}</li>
 * <li>{@link getTripTemplate($tripTemplateId)}</li>
 * <li>{@link deleteTripTemplate($tripTemplateId)}</li>
 * <li>{@link createDayprogram()}</li>
 * <li>{@link deleteDayprogram($dayprogramId)}</li>
 * <li>{@link changeBookableOfTripTemplate($tripTemplateId)}</li>
 * <li>{@link bookTrip()}</li>
 * <li>{@link cancelTrip($tripId)}</li>
 * <li>{@link getBookedTrip($tripId)}</li>
 * <li>{@link lockInvoicesRegistered($tripId)}</li>
 * <li>{@link unlockInvoicesRegistered($tripId)}</li>
 * </ul>
 * @author Lukas
 */
class TripController {
    
    /**
     * Creates a new {@link TripTemplate}
     * @return boolean|int
     */
    public static function createTripTemplate(){
        if(!isset($_SESSION['role']) or (isset($_SESSION['role']) and $_SESSION['role'] != "admin")){
            return false;
        }
        $tripTemplate = new TripTemplate();
        $tripTemplate->setName(\filter_input(\INPUT_POST, 'name', \FILTER_SANITIZE_STRING));
        $tripTemplate->setDescription(\filter_input(\INPUT_POST, 'description', \FILTER_SANITIZE_STRING));
        $minAllocation = Validation::positiveInt(\filter_input(\INPUT_POST, 'minAllocation', \FILTER_VALIDATE_INT));
        if(!$minAllocation){
            return false;
        }
        $tripTemplate->setMinAllocation($minAllocation);
        $maxAllocation = Validation::positiveInt(\filter_input(\INPUT_POST, 'maxAllocation', \FILTER_VALIDATE_INT));
        if(!$maxAllocation){
            return false;
        }
        $tripTemplate->setMaxAllocation($maxAllocation);
        if(isset($_FILES['img'])){
            $upload = Upload::uploadImage();
            if(!$upload){
                return false;
            }
            $tripTemplate->setPicturePath($upload);
        }else{
            return false;
        }
        $fk_bus_id = Validation::positiveInt(\filter_input(\INPUT_POST, 'busId', \FILTER_VALIDATE_INT));
        if(!$fk_bus_id){
            return false;
        }
        $tripTemplate->setFkBusId($fk_bus_id);
        
        $success = $tripTemplate->create();
        if($success){
            return $success;
        }
        return false;
    }
    
    /**
     * Provides the view of all {@link TripTemplate} dependant of the role
     * <ul>
     * <li>Admin: Gets all available {@link TripTemplate}(finished & unfinished)</li>
     * <li>User & unregistered: Gets all {@link TripTemplate} which are bookable</li>
     * </ul>
     * @return boolean
     */
    public static function getAllTrips(){
        $tripDBC = new TripDBC();
        $homepage = new TemplateView("allTrips.php");
        if(isset($_SESSION['role']) and $_SESSION['role'] == "admin"){
            $homepage->tripTemplates = $tripDBC->getAllTripTemplates();
            $homepage->trips = $tripDBC->getBookedTrips();
            LayoutRendering::basicLayout($homepage);
            return true;
        }else{
            $homepage->tripTemplates = $tripDBC->getBookableTripTemplates();
            if(isset($_SESSION['login'])){
                $homepage->trips = $tripDBC->getBookedTrips($_SESSION['userId']);
                LayoutRendering::basicLayout($homepage, "headerUserLoggedIn");
                return true;
            }else{
                LayoutRendering::basicLayout($homepage, "headerLoggedOut");
                return true;
            }
        }
        
    }
    
    /**
     * Provides the view of all {@link TripTemplate} for a good admin overview
     * @return boolean
     */
    public static function getAllTripTemplates(){
        if(isset($_SESSION['role']) and $_SESSION['role'] == "admin"){
            $tripDBC = new TripDBC();
            $busDBC = new BusDBC();
            $tripTemplates = new TemplateView("adminTripTemplates.php");
            $tripTemplates->buses = $busDBC->getAllBuses();
            $tripTemplates->tripTemplates = $tripDBC->getAllTripTemplates();
            LayoutRendering::basicLayout($tripTemplates);
            return true;
        }else{
            return false;
        }
    }
    
    /**
     * Provides the view of a specific {@link TripTemplate}<br>
     * How the overview looks like is dependant of the role
     * @param int $tripTemplateId
     * @return boolean
     */
    public static function getTripTemplate($tripTemplateId){
        $tripTemplate = new TripTemplate();
        $id = Validation::positiveInt($tripTemplateId);
        if(!$id){
            return false;
        }
        $tripTemplate->setId($id);
        
        if(isset($_SESSION['role']) and $_SESSION['role'] == "admin"){
            $hotelDBC = new HotelDBC();
            $adminTripOverview = new TemplateView("adminUnbookedTripOverview.php");
            $adminTripOverview->tripTemplate = $tripTemplate->find();
            $adminTripOverview->hotels = $hotelDBC->findAllHotels();
            LayoutRendering::basicLayout($adminTripOverview);
            return true;
        }else{
            $userTripOverview = new TemplateView("userUnbookedTripOverview.php");
            $userTripOverview->tripTemplate = $tripTemplate->find();
            //Loads booking relevant data
            if(isset($_SESSION['login'])){
                $user = new User();
                $user->setId($_SESSION['userId']);
                $user = $user->findParticipants();
                $userTripOverview->user = $user;
                $insuranceDBC = new InsuranceDBC();
                $insurances = $insuranceDBC->getAllInsurances();
                $userTripOverview->insurances = $insurances;
                LayoutRendering::basicLayout($userTripOverview, "headerUserLoggedIn");
                return true;
            }else{
                LayoutRendering::basicLayout($userTripOverview, "headerLoggedOut");
                return true;
            }
        }
        
    }
    
    /**
     * Deletes a {@link TripTemplate} by the given id
     * @param int $tripTemplateId
     * @return boolean
     */
    public static function deleteTripTemplate($tripTemplateId){
        if(!isset($_SESSION['role']) or (isset($_SESSION['role']) and $_SESSION['role'] != "admin")){
            return false;
        }
        $tripTemplate = new TripTemplate();
        $id = Validation::positiveInt($tripTemplateId);
        if(!id){
            return false;
        }
        $tripTemplate->setId($id);
        
        $success = $tripTemplate->delete();
        if($success){
            return $success;
        }
        return false;
    }
    
    /**
     * Creates a new {@link Dayprogram} according to the specified {@link TripTemplate}
     * @return boolean|id
     */
    public static function createDayprogram(){
        if(!isset($_SESSION['role']) or (isset($_SESSION['role']) and $_SESSION['role'] != "admin")){
            return false;
        }
        
        //stores the Dayprogram
        $dayprogram = new Dayprogram();
        $fk_tripTemplate_id = Validation::positiveInt(\filter_input(\INPUT_POST, 'tripTemplateId', \FILTER_VALIDATE_INT));
        if(!$fk_tripTemplate_id){
            return false;
        }
        $dayprogram->setFkTripTemplateId($fk_tripTemplate_id);
        $dayprogram->setName(\filter_input(\INPUT_POST, 'name', \FILTER_SANITIZE_STRING));
        $dayNumber = Validation::positiveInt(\filter_input(\INPUT_POST, 'dayNumber', \FILTER_VALIDATE_INT));
        if(!$dayNumber){
            return false;
        }
        $dayprogram->setDayNumber($dayNumber);
        $dayprogram->setDescription(\filter_input(\INPUT_POST, 'description', \FILTER_SANITIZE_STRING));
        echo "fk_hotel_id: ".\filter_input(\INPUT_POST, 'hotelId', \FILTER_VALIDATE_INT)."</br>";
        $fk_hotel_id = Validation::positiveInt(\filter_input(\INPUT_POST, 'hotelId', \FILTER_VALIDATE_INT));
        if($fk_hotel_id === false){
            return false;
        }
        $dayprogram->setFkHotelId($fk_hotel_id);
        $img = $_FILES['img'];
        if($img){
            $upload = Upload::uploadImage();
            if(!$upload){
                return false;
            }
            $dayprogram->setPicturePath($upload);
        }else{
        }
        $success = $dayprogram->create();
        if($success){
            return $fk_tripTemplate_id;//to ensure correct routing
        }
        return false;
    }
    
    /**
     * Deletes a {@link Dayprogram} by the given id
     * @param int $dayprogramId
     * @return boolean
     */
    public static function deleteDayprogram($dayprogramId){
        if(!isset($_SESSION['role']) or (isset($_SESSION['role']) and $_SESSION['role'] != "admin")){
            return false;
        }
        
        $dayprogram = new Dayprogram();
        $id = Validation::positiveInt($dayprogramId);
        if(!$id){
            return false;
        }
        $dayprogram->setId($id);
        
        $success = $dayprogram->delete();
        if($success){
            return $success;
        }
        return false;
    }
    
    /**
     * Changes the bookability of the specified {@link TripTemplate} to provide access to the {@link User} to book it
     * @param int $tripTemplateId
     * @return boolean
     */
    public static function changeBookableOfTripTemplate($tripTemplateId){
        if(!isset($_SESSION['role']) or (isset($_SESSION['role']) and $_SESSION['role'] != "admin")){
            return false;
        }
        $tripTemplate = new TripTemplate();
        
        $id = Validation::positiveInt($tripTemplateId);
        if(!$id){
            return false;
        }
        $tripTemplate->setId($id);
        
        $success = $tripTemplate->changeBookable();
        if($success){
            return $success;
        }
        return false;
    }
    
    /**
     * Books a {@link Trip} with the {@link User} logged in
     * @return boolean|int
     */
    public static function bookTrip(){
        if(!isset($_SESSION['login'])){
            return false;
        }
        $trip = new Trip();
        
        $fkTripTemplateId = Validation::positiveInt(\filter_input(\INPUT_POST, 'tripTemplateId', \FILTER_VALIDATE_INT));
        if(!$fkTripTemplateId){
            return false;
        }
        $trip->setFkTripTemplateId($fkTripTemplateId);
        
        //Adds the participants
        $participantIds = \filter_input(\INPUT_POST, 'participants', \FILTER_VALIDATE_INT, \FILTER_REQUIRE_ARRAY);
        if($participantIds){
            foreach($participantIds as $participantId){
                if(!Validation::positiveInt($participantId)){
                    return false;
                }
            }
        }else{
            return false;
        }
        $trip->setParticipantIds($participantIds);
        $trip->setNumOfParticipation(sizeof($participantIds)+1);//+1 to count the User 
        
        $departureDate = Validation::upToDate(\filter_input(\INPUT_POST, 'departureDate', \FILTER_SANITIZE_STRING));
        if(!$departureDate){
            return false;
        }
        $trip->setDepartureDate($departureDate);
        $trip->setFkUserId($_SESSION['userId']);
        $insuranceId = Validation::positiveInt(\filter_input(\INPUT_POST, 'insurance', \FILTER_VALIDATE_INT));
        if($insuranceId === false){
            return false;
        }
        $trip->setFkInsuranceId($insuranceId);

        $success = $trip->book();
        
        /**
        * PHPMailer does not work with herokuapp. For this reason we check if we are on the herokuapp or on localhost.
        * If we are on localhost we send the invoice.
         * 
        * @author Vanessa Cajochen
        */
        if($success){
                if($GLOBALS['ROOT_URL'] == 'http://localhost/WE_Reiseunternehmen'){
                $trip->getId();
                $_SESSION['pdfOutput'] = 'F';
                $_SESSION['tripId'] = $success;
                include 'pdf/customerInvoice.php';     
                include 'mail/sendMail.php'; 
                unset($_SESSION['tripId']);
                unset($_SESSION['pdfOutput']);
                if(file_exists('pdf/tempInvoices/'.$success.'.pdf')){
                    unlink('pdf/tempInvoices/'.$success.'.pdf');
                }
                }
            return $success;
        }
        return false;
    }
    
    /**
     * Deletes a {@link Trip} by the given id
     * @param int $tripId
     * @return boolean
     */
    public static function cancelTrip($tripId){
        if(!isset($_SESSION['role']) or (isset($_SESSION['role']) and $_SESSION['role'] != "admin")){
            return false;
        }
        $id = Validation::positiveInt($tripId);
        if(!$id){
            return false;
        }
        $trip = new Trip();
        $trip->setId($id);
        
        $success = $trip->cancel();
        if($success){
            return $success;
        }
        return false;
    }
    
    /**
     * Provides the view of the specified booked {@link Trip}<br>
     * Also access to {@link TripTemplate}, {@link Bus}, {@link Hotel}, {@link Dayprogram} and {@link Insurance}
     * @param int $tripId
     * @return boolean
     */
    public static function getBookedTrip($tripId){
        $id = Validation::positiveInt($tripId);
        if(!$id){
            return false;
        }
        $trip = new Trip();
        $trip->setId($id);
        
        if(isset($_SESSION['role']) and $_SESSION['role'] == "admin"){
            $adminBookedTripOverview = new TemplateView("adminBookedTripOverview.php");
            $adminBookedTripOverview->trip = $trip->find();
            LayoutRendering::basicLayout($adminBookedTripOverview);
            return true;
        }else if(isset($_SESSION['role']) and $_SESSION['role'] == "user"){
            $userBookedTripOverview = new TemplateView("userBookedTripOverview.php");
            $userBookedTripOverview->trip = $trip->find();
            LayoutRendering::basicLayout($userBookedTripOverview, "headerUserLoggedIn");
            return true;
        }
    }
    
    /**
     * Locks the InvoiceRegistered of the specified {@link Trip} to close the {@link Invoice} allocation
     * @param int $tripId
     * @return boolean
     */
    public static function lockInvoicesRegistered($tripId){
        if(!isset($_SESSION['role']) or (isset($_SESSION['role']) and $_SESSION['role'] != "admin")){
            return false;
        }
        $id = Validation::positiveInt($tripId);
        if(!$id){
            return false;
        }
        $trip = new Trip();
        $trip->setId($id);
        
        $success = $trip->lockInvoicesRegistered();
        if($success){
            return $success;
        }
        return false;
    }
    
    /**
     * Locks the InvoiceRegistered of the specified {@link Trip} to allow to allocate more {@link Invoice}
     * @param int $tripId
     * @return boolean
     */
    public static function unlockInvoicesRegistered($tripId){
        if(!isset($_SESSION['role']) or (isset($_SESSION['role']) and $_SESSION['role'] != "admin")){
            return false;
        }
        $id = Validation::positiveInt($tripId);
        if(!$id){
            return false;
        }
        $trip = new Trip();
        $trip->setId($id);
        
        $success = $trip->unlockInvoicesRegistered();
        if($success){
            return $success;
        }
        return false;
    }
    
}
