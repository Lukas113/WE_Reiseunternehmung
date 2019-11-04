<?php

namespace database;


/**
 * Provides secure access to the {@link Hotel} related queries
 * <ul>
 * <li>{@link createHotel($hotel)}</li>
 * <li>{@link deleteHotel($hotel)}</li>
 * <li>{@link findAllHotels()}</li>
 * <li>{@link findHotelById($hotelId, $close)}</li>
 * </ul>
 *
 * @author Lukas
 */
class HotelDBC extends DBConnector {
    
    /**
     * Stores the {@link Hotel} into the database
     * @param Hotel $hotel
     * @return boolean|int
     */
    public function createHotel($hotel){
        $stmt = $this->mysqliInstance->prepare("INSERT INTO hotel VALUES (NULL, ?, ?, ?, ?)");
        if(!$stmt){
            return false;
        }
        $stmt->bind_param('ssds', $name, $description, $pricePerPerson, $picturePath);
        $name = $hotel->getName();
        $description = $hotel->getDescription();
        $pricePerPerson = $hotel->getPricePerPerson();
        $picturePath = $hotel->getPicturePath();
        return $this->executeInsert($stmt);
    }
    
    /**
     * Deletes the {@link Hotel} by the given id
     * @param Hotel $hotel
     * @return boolean
     */
    public function deleteHotel($hotel){
        $hotel = $this->findHotelById($hotel->getId());
        if(!$hotel){
            return false;
        }
        $stmt = $this->mysqliInstance->prepare("DELETE FROM hotel WHERE id = ?");
        if(!$stmt){
            return false;
        }
        $stmt->bind_param('i', $id);
        $id = $hotel->getId();
        $result = $this->executeDelete($stmt);
        $picturePath = $hotel->getPicturePath();
        if(file_exists($picturePath)  and strpos($picturePath, 'default') == false){
            unlink($picturePath);
        }
        return $result;
    }
    
    /**
     * Finds all {@link Hotel} ordered by name asc
     * @return boolean|array
     */
    public function findAllHotels(){
        $stmt = $this->mysqliInstance->prepare("SELECT * FROM hotel ORDER BY name ASC");
        if(!$stmt){
            return false;
        }
        $stmt->execute();
        $hotels = array();
        $result = $stmt->get_result();
        while($hotel = $result->fetch_object("entities\Hotel")){
            array_push($hotels, $hotel);
        }
        
        $stmt->close();
        return $hotels;
    }
    
    /**
     * Finds the {@link Hotel} with the given id
     * @param int $hotelId
     * @param boolean $close false if closing of the database connection is NOT desired)
     * @return boolean
     */
    public function findHotelById($hotelId, $close = true){
        $stmt = $this->mysqliInstance->prepare("SELECT * FROM hotel where id = ?;");
        if(!$stmt){
            return false;
        }
        $stmt->bind_param('i', $id);
        $id = $hotelId;
        $stmt->execute();
        $hotelObj = $stmt->get_result()->fetch_object("entities\Hotel");
        
        if($close){
            $stmt->close();
        }
        
        //checks whether the Hotel exists
        if($hotelObj){
            return $hotelObj;
        }else{
            return false;
        }
    }
    
    
}
