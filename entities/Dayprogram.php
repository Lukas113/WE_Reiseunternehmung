<?php

namespace entities;
use database\TripDBC;

/**
 * Ensure easy access to {@link Dayprogram} related functionalities and data
 * <ul>
 * <li>{@link create()}</li>
 * <li>{@link delete()}</li>
 * </ul>
 * @author Lukas
 */
class Dayprogram {
    
    private $id;
    private $name;
    private $picturePath;
    private $dayNumber;
    private $description;
    private $fk_tripTemplate_id;
    private $fk_hotel_id;
    private $hotel;
    private $tripDBC;
    
    public function __construct() {
        $this->tripDBC = new tripDBC();
    }
    
    /**
     * Creates a new {@link Dayprogram}<br>
     * Variables must be set
     * @return boolean|int
     */
    public function create(){
        return $this->tripDBC->createDayprogram($this);
    }
    
    /**
     * Deletes the {@link Dayprogram}<br>
     * id must be set
     * @return boolean
     */
    public function delete(){
        return $this->tripDBC->deleteDayprogram($this);
    }

    
    
    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getPicturePath() {
        return $this->picturePath;
    }

    public function getDayNumber() {
        return $this->dayNumber;
    }

    public function getDescription() {
        return $this->description;
    }
    
    public function getFkTripTemplateId(){
        return $this->fk_tripTemplate_id;
    }
    
    public function getFkHotelId(){
        return $this->fk_hotel_id;
    }
    
    public function getHotel(){
        return $this->hotel;
    }

    public function setId($id) {
        /* @var $id type int*/
        $this->id = (int) $id;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function setPicturePath($picturePath) {
        $this->picturePath = $picturePath;
    }

    public function setDayNumber($dayNumber) {
        /* @var $dayNumber type int*/
        $this->dayNumber = (int) $dayNumber;
    }

    public function setDescription($description) {
        $this->description = $description;
    }
    
    public function setFkTripTemplateId($fk_tripTemplate_id){
        /* @var $fk_tripTemplate_id type int*/
        $this->fk_tripTemplate_id = (int) $fk_tripTemplate_id;
    }
    
    public function setFkHotelId($fk_hotel_id){
        /* @var $fk_hotel_id type int*/
        $this->fk_hotel_id = (int) $fk_hotel_id;
    }
    
    public function setHotel($hotel){
        $this->hotel = $hotel;
    }
    
}
