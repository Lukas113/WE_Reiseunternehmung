<?php

namespace database;


/**
 * Provides secure access to the {@link Insurance} related queries
 * <ul>
 * <li>{@link createInsurance($insurance)}</li>
 * <li>{@link deleteInsurance($insurance)}</li>
 * <li>{@link getAllInsurances()}</li>
 * <li>{@link findInsuranceById($insuranceId)}</li>
 * </ul>
 * @author Lukas
 */
class InsuranceDBC extends DBConnector {
    
    /**
     * Stores the {@link Insurance} into the database
     * @param Insurance $insurance
     * @return boolean|int
     */
    public function createInsurance($insurance){
        $stmt = $this->mysqliInstance->prepare("INSERT INTO insurance VALUES (NULL, ?, ?, ?)");
        if(!$stmt){
            return false;
        }
        $stmt->bind_param('ssd', $name, $description, $pricePerPerson);
        $name  = $insurance->getName();
        $description = $insurance->getDescription();
        $pricePerPerson = $insurance->getPricePerPerson();
        
        return $this->executeInsert($stmt);
    }
    
    /**
     * Deletes the {@link Insurance} by the given id
     * @param Insurance $insurance
     * @return boolean
     */
    public function deleteInsurance($insurance){
        $stmt = $this->mysqliInstance->prepare("DELETE FROM insurance WHERE id = ?");
        if(!$stmt){
            return false;
        }
        $stmt->bind_param('i', $id);
        $id = $insurance->getId();
        return $this->executeDelete($stmt);
    }
    
    /**
     * Gets all available {@link Insurance} from the database
     * @return boolean|array
     */
    public function getAllInsurances(){
        $stmt = $this->mysqliInstance->prepare("SELECT * FROM insurance ORDER BY name ASC");
        if(!$stmt){
            return false;
        }
        $stmt->execute();
        $insurances = array();
        $result = $stmt->get_result();
        while($insurance = $result->fetch_object("entities\Insurance")){
            array_push($insurances, $insurance);
        }

        $stmt->close();
        return $insurances;
    }
    
    /**
     * Finds the {@link Insurance} by the given id
     * @param int $insuranceId
     * @return boolean|Insurance
     */
    public function findInsuranceById($insuranceId){
        $stmt = $this->mysqliInstance->prepare("SELECT * FROM insurance where id = ?;");
        if(!$stmt){
            return false;
        }
        $stmt->bind_param('i', $id);
        $id = $insuranceId;
        $stmt->execute();
        $insuranceObj = $stmt->get_result()->fetch_object("entities\Insurance");
        
        //checks whether the Insurance exists
        if($insuranceObj){
            return $insuranceObj;
        }else{
            return false;
        }
    }
    
}
