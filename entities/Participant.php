<?php

namespace entities;

use database\UserDBC;

/**
 * Ensure easy access to {@link Participant} related functionalities and data
 * <ul>
 * <li>{@link create()}</li>
 * <li>{@link delete()}</li>
 * </ul>
 * @author Lukas
 */
class Participant {
    
    private $id;
    private $firstName;
    private $lastName;
    private $birthDate;
    private $fk_user_id;
    private $userDBC;
    
    public function __construct() {
        $this->userDBC = new UserDBC();
    }
    
    /**
     * Creates the {@link Participant}<br>
     * Variables must be set
     * @return boolean|int
     */
    public function create(){
        return $this->userDBC->createParticipant($this);
    }
    
    /**
     * Deletes the {@link Participant}<br>
     * id must be set
     * @return boolean
     */
    public function delete(){
        return $this->userDBC->deleteParticipant($this);
    }
    
    
    public function getId() {
        return $this->id;
    }

    public function getFirstName() {
        return $this->firstName;
    }

    public function getLastName() {
        return $this->lastName;
    }

    public function getBirthDate() {
        return $this->birthDate;
    }
    
    public function getFkUserId(){
        return $this->fk_user_id;
    }
    
    public function setId($id) {
        /* @var $id type int*/
        $this->id = (int) $id;
    }

    public function setFirstName($firstName) {
        $this->firstName = $firstName;
    }

    public function setLastName($lastName) {
        $this->lastName = $lastName;
    }

    public function setBirthDate($birthDate) {
        $this->birthDate = $birthDate;
    }
    
    public function setFkUserId($fk_user_id){
        /* @var $fk_user_id type int*/
        $this->fk_user_id = (int) $fk_user_id;
    }

}
