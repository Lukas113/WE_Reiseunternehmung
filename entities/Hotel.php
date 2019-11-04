<?php

namespace entities;

use database\HotelDBC;

/**
 * Ensure easy access to {@link Hotel} related functionalities and data
 * <ul>
 * <li>{@link create()}</li>
 * <li>{@link delete()}</li>
 * </ul>
 * @author Lukas
 */
class Hotel {
    
    private $id;
    private $name;
    private $description;
    private $pricePerPerson;
    private $picturePath;
    private $hotelDBC;
    
    public function __construct() {
        $this->hotelDBC = new HotelDBC();
    }
    
    /**
     * Creates the {@link Hotel}<br>
     * Variables must be set
     * @return boolean|int
     */
    public function create(){
        return $this->hotelDBC->createHotel($this);
    }
    
    /**
     * Deletes the {@link Hotel}<br>
     * id must be set
     * @return boolean
     */
    public function delete(){
        return $this->hotelDBC->deleteHotel($this);
    }
    
    
    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getPricePerPerson() {
        return $this->pricePerPerson;
    }

    public function getPicturePath() {
        return $this->picturePath;
    }

    function setId($id) {
        /* @var $id type int*/
        $this->id = (int) $id;
    }

    function setName($name) {
        $this->name = $name;
    }

    function setDescription($description) {
        $this->description = $description;
    }

    function setPricePerPerson($pricePerPerson) {
        /* @var $pricePerPerson type double*/
        $this->pricePerPerson = (double) $pricePerPerson;
    }

    function setPicturePath($picturePath) {
        $this->picturePath = $picturePath;
    }

}
