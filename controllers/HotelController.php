<?php

namespace controllers;

use entities\Hotel;
use database\HotelDBC;
use helpers\Upload;
use helpers\Validation;
use views\LayoutRendering;
use views\TemplateView;

/**
 * Controls the access to the functionalities of the {@link Hotel}
 * <ul>
 * <li>{@link createHotel()}</li>
 * <li>{@link deleteHotel($id)}</li>
 * <li>{@link getAllHotels()}</li>
 * </ul>
 *
 * @author Lukas
 */
class HotelController {
    
    /**
     * Creates a new {@link Hotel}
     * @return boolean|int
     */
    public static function createHotel(){
        if(!isset($_SESSION['role']) or (isset($_SESSION['role']) and $_SESSION['role'] != "admin")){
            return false;
        }
        $hotel = new Hotel();
        
        $hotel->setName(\filter_input(\INPUT_POST, 'name', \FILTER_SANITIZE_STRING));
        $hotel->setDescription(\filter_input(\INPUT_POST, 'description', \FILTER_SANITIZE_STRING));
        $hotelPrice = Validation::positivePrice(\filter_input(\INPUT_POST, 'pricePerPerson', \FILTER_SANITIZE_STRING));
        if(!$hotelPrice){
            return false;
        }
        $hotel->setPricePerPerson($hotelPrice);
        if(isset($_FILES['img'])){
            $upload = Upload::uploadImage();
            if(!$upload){
                return false;
            }
            $hotel->setPicturePath($upload);
        }else{
            return false;
        }
        $success = $hotel->create();
        if($success){
            return $success;
        }
        return false;
    }
    
    /**
     * Deletes  a {@link Hotel} by the given id
     * @param int $id
     * @return boolean
     */
    public static function deleteHotel($id){
        if(!isset($_SESSION['role']) or (isset($_SESSION['role']) and $_SESSION['role'] != "admin")){
            return false;
        }
        $hotel = new Hotel();
        
        $id = Validation::positiveInt($id);
        if(!$id){
            return false;
        }
        $hotel->setId($id);
        
        $success = $hotel->delete();
        if($success){
            return $success;
        }
        return false;
    }
    
    /**
     * Provides the view of all stored {@link Hotel} from the database
     * @return boolean
     */
    public static function getAllHotels(){
        if(isset($_SESSION['role']) and $_SESSION['role'] == "admin"){
            $hotelDBC = new HotelDBC();
            
            $hotelView = new TemplateView("adminHotels.php");
            $hotelView->hotels = $hotelDBC->findAllHotels();
            LayoutRendering::basicLayout($hotelView);
            return true;
        }else{
            return false;
        }
    }
}
