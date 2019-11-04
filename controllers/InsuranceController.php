<?php

namespace controllers;

use entities\Insurance;
use database\InsuranceDBC;
use helpers\Validation;
use views\LayoutRendering;
use views\TemplateView;

/**
 * Controls the access to the functionalities of the {@link Insurance}
 * <ul>
 * <li>{@link createInsurance()}</li>
 * <li>{@link deleteInsurance($id)}</li>
 * <li>{@link getAllInsurances()}</li>
 * </ul>
 * @author Lukas
 */
class InsuranceController {
    
    /**
     * Creates a new {@link Insurance}
     * @return boolean|int
     */
    public static function createInsurance(){
        if(!isset($_SESSION['role']) or (isset($_SESSION['role']) and $_SESSION['role'] != "admin")){
            return false;
        }
        $insurance = new Insurance();
        
        $insurance->setName(\filter_input(\INPUT_POST, 'name', \FILTER_SANITIZE_STRING));
        $insurance->setDescription(\filter_input(\INPUT_POST, 'description', \FILTER_SANITIZE_STRING));
        $pricePerPerson = Validation::positivePrice(\filter_input(\INPUT_POST, 'pricePerPerson', \FILTER_SANITIZE_STRING));
        if(!$pricePerPerson){
            return false;
        }
        $insurance->setPricePerPerson($pricePerPerson);
        
        $success = $insurance->create();
        if($success){
            return $success;
        }
        return false;
    }
    
    /**
     * Deletes an {@link Insurance}
     * @param int $id
     * @return boolean
     */
    public static function deleteInsurance($id){
        if(!isset($_SESSION['role']) or (isset($_SESSION['role']) and $_SESSION['role'] != "admin")){
            return false;
        }
        $insurance = new Insurance();
        
        $id = Validation::positiveInt($id);
        if(!$id){
            return false;
        }
        $insurance->setId($id);
        
        $success = $insurance->delete();
        if($success){
            return $success;
        }
        return false;
    }
    
    /**
     * Provides the view of all stored {@link Insurance} from the database
     * @return boolean
     */
    public static function getAllInsurances(){
        if(isset($_SESSION['role']) and $_SESSION['role'] == "admin"){
            $insuranceDBC = new InsuranceDBC();
            
            $insuranceView = new TemplateView("adminInsurances.php");
            $insuranceView->insurances = $insuranceDBC->getAllInsurances();
            LayoutRendering::basicLayout($insuranceView);
            return true;
        }else{
            return false;
        }
    }
    
}
