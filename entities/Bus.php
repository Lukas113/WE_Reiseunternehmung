<?php

namespace entities;

use database\BusDBC;

/**
 * Ensure easy access to {@link Bus} related functionalities and data
 * <ul>
 * <li>{@link create()}</li>
 * <li>{@link find()}</li>
 * <li>{@link delete()}</li>
 * </ul>
 * @author Lukas
 */
class Bus {
    
    private $id;
    private $name;
    private $description;
    private $seats;
    private $pricePerDay;
    private $picturePath;
    private $busDBC;
    
    public function __construct() {
        $this->busDBC = new BusDBC();
    }
    
    /**
     * Creates the {@link Bus}<br>
     * Variables must be set
     * @return boolean|int
     */
    public function create(){
        return $this->busDBC->createBus($this);
    }
    
    /**
     * Finds the {@link Bus}<br>
     * id must be set
     * @return boolean|Bus
     */
    public function find(){
        return $this->busDBC->findBusById($this->id);
    }
    
    /**
     * Deletes the {@link Bus}<br>
     * id must be set
     * @return boolean
     */
    public function delete(){
        return $this->busDBC->deleteBus($this);
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

    public function getSeats() {
        return $this->seats;
    }

    public function getPricePerDay() {
        return $this->pricePerDay;
    }

    public function getPicturePath() {
        return $this->picturePath;
    }

    public function setId($id) {
        /* @var $id type int*/
        $this->id = (int) $id;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    public function setSeats($seats) {
        /* @var $seats type int*/
        $this->seats = (int) $seats;
    }

    public function setPricePerDay($pricePerDay) {
        /* @var $pricePerDay type double*/
        $this->pricePerDay = (double) $pricePerDay;
    }

    public function setPicturePath($picturePath) {
        $this->picturePath = $picturePath;
    }

}
