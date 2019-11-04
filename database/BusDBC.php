<?php

namespace database;

use entities\Bus;

/**
 * Provides secure access to the database of {@link Bus} releated queries
 * <ul>
 * <li>{@link createBus($bus)}</li>
 * <li>{@link findBusById($busId, $close)}</li>
 * <li>{@link deleteBus($bus)}</li>
 * <li>{@link getAllBuses()}</li>
 * </ul>
 *
 * @author Lukas
 */
class BusDBC extends DBConnector {
    
    /**
     * Stores the {@link Bus} into the database
     * @param Bus $bus
     * @return boolean|int
     */
    public function createBus($bus){
        $stmt = $this->mysqliInstance->prepare("INSERT INTO bus VALUES (NULL, ?, ?, ?, ?, ?)");
        if(!$stmt){
            return false;
        }
        $stmt->bind_param('ssids', $name, $description, $seats, $pricePerDay, $picturePath);
        $name  = $bus->getName();
        $description = $bus->getDescription();
        $seats = $bus->getSeats();
        $pricePerDay = $bus->getPricePerDay();
        $picturePath = $bus->getPicturePath();
        return $this->executeInsert($stmt);
    }
    
    /**
     * Finds the {@link Bus} by the given id
     * @param int $busId
     * @param boolean $close (false if closing of connection is NOT desired)
     * @return boolean|Bus
     */
    public function findBusById($busId, $close = true){
        $stmt = $this->mysqliInstance->prepare("SELECT * FROM bus where id = ?;");
        if(!$stmt){
            return false;
        }
        $stmt->bind_param('i', $id);
        $id = $busId;
        $stmt->execute();
        $busObj = $stmt->get_result()->fetch_object("entities\Bus");
        
        if($close){
            $stmt->close();
        }
        
        //checks whether the Bus exists
        if($busObj){
            return $busObj;
        }else{
            return false;
        }
    }
    
    /**
     * Deletes the {@link Bus} by the given id
     * @param Bus $bus
     * @return boolean
     */
    public function deleteBus($bus){
        $bus = $this->findBusById($bus->getId());
        $stmt = $this->mysqliInstance->prepare("DELETE FROM bus WHERE id = ?");
        if(!$stmt){
            return false;
        }
        $stmt->bind_param('i', $id);
        $id = $bus->getId();
        
        $result = $this->executeDelete($stmt);
        $picturePath = $bus->getPicturePath();
        if(file_exists($picturePath) and strpos($picturePath, 'default') == false){
            unlink($picturePath);
        }
        return $result;
    }
    
    /**
     * Gets all available {@link Bus} from the database
     * @return boolean|array
     */
    public function getAllBuses(){
        $stmt = $this->mysqliInstance->prepare("SELECT * FROM bus ORDER BY name ASC");
        if(!$stmt){
            return false;
        }
        $stmt->execute();
        $buses = array();
        $result = $stmt->get_result();
        while($bus = $result->fetch_object("entities\Bus")){
            array_push($buses, $bus);
        }

        $stmt->close();
        return $buses;
    }
    
}
