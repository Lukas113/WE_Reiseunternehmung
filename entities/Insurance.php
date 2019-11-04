<?php

namespace entities;

use database\InsuranceDBC;
use helpers\Margin;
use helpers\Numbers;

/**
 * Ensure easy access to {@link Insurance} related functionalities and data
 * <ul>
 * <li>{@link create()}</li>
 * <li>{@link delete()}</li>
 * </ul>
 * @author Lukas
 */
class Insurance {
    
    private $id;
    private $name;
    private $description;
    private $pricePerPerson;
    private $insuranceDBC;
    
    public function __construct() {
        $this->insuranceDBC = new InsuranceDBC();
    }
    
    /**
     * Creates the {@link Insurance}<br>
     * Variables must be set
     * @return boolean|int
     */
    public function create(){
        return $this->insuranceDBC->createInsurance($this);
    }
    
    /**
     * Deletes the {@link Bus}<br>
     * id must be set
     * @return boolean
     */
    public function delete(){
        return $this->insuranceDBC->deleteInsurance($this);
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
    
    public function getCustomerPricePerPerson(){
        return Numbers::roundPrice(Margin::addInsurance($this->pricePerPerson));
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    public function setPricePerPerson($pricePerPerson) {
        $this->pricePerPerson = $pricePerPerson;
    }
    
}
