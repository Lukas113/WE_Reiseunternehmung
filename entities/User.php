<?php

namespace entities;

use database\UserDBC;

/**
 * Ensure easy access to {@link User} related functionalities and data
 * <ul>
 * <li>{@link register()}</li>
 * <li>{@link login()}</li>
 * <li>{@link loginPreCheck()}</li>
 * <li>{@link logout()}</li>
 * <li>{@link delete()}</li>
 * <li>{@link findParticipants()}</li>
 * <li>{@link changeRole()}</li>
 * <li>{@link bookTrip($trip, $insurance)}</li>
 * </ul>
 * @author Lukas
 */
class User {
    
    private $id;
    private $firstName;
    private $lastName;
    private $gender;
    private $street;
    private $zipCode;
    private $location;
    private $email;
    private $role;
    private $birthDate;
    private $password;
    private $participants;
    private $lastBooking;
    private $userDBC;
    
    public function __construct() {
        $this->userDBC = new UserDBC();
    }

    /**
     * Registers the {@link User}<br>
     * Validation if the {@link User} email does already exist<br>
     * If registration is successful, then settings of the $_SESSION variables will be performed:
     * <ul>
     * <li>userId int</li>
     * <li>login boolean</li>
     * <li>role String</li>
     * </ul>
     */
    public function register(){
        if($this->userDBC->findUserByEmail($this)){
            //doublicate of e-mails are not allowed
            return false;
        }
        
        $this->password = password_hash($this->password, PASSWORD_DEFAULT);
        $userId = $this->userDBC->createUser($this);
        if($userId){
            session_regenerate_id();
            if((isset($_SESSION['role']) and $_SESSION['role'] == "admin")){
                //nothing toDo here
            }else{
                $_SESSION['userId'] = $userId;
                $_SESSION['login'] = true;
                $_SESSION['role'] = $this->role;
            }
            return $userId;
        }else{
            //creation failed
            return false;
        }
    }
    
    /** 
     * Logs the {@link User} in if email and password are valid<br>
     * Rehash of the password if necessary inclusive<br>
     * If login is successful, then settings of the $_SESSION variables will be performed:
     * <ul>
     * <li>userId int</li>
     * <li>login boolean</li>
     * <li>role String</li>
     * </ul>
     */
    public function login(){
        $userObj = $this->userDBC->findUserByEmail($this);
        if($userObj){
            $password = $userObj->getPassword();
        }else{
            //User doesn't exist
            return false;
        }
        
        if (password_verify($this->password, $password)) {
            if (password_needs_rehash($password, PASSWORD_DEFAULT)) {
                $reHashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $this->password = $reHashedPassword;
                $this->userDBC->updatePassword($this);
            }
            session_regenerate_id();
            $_SESSION['userId'] = $userObj->getId();
            $_SESSION['login'] = true;
            $_SESSION['role'] = $userObj->getRole();
            return true;
        }else{
            //password is incorrect
            return false;
        }
    }
    
    
    
    /**
    * Checks if the email already exists.
    * If the email exists, it will check if the entered password is correct.
    *
    * @author Vanessa Cajochen
    */
    public function loginPreCheck(){
        $userObj = $this->userDBC->findUserByEmail($this);
        if($userObj){
            $password = $userObj->getPassword();
        }else{
            //User doesn't exist
            return false;
        }
        
        if (password_verify($this->password, $password)) {                        
            return true;
        }else{
            //password is incorrect
            return false;
        }
    }
    
    
    /**
     * Logs the {@link User} out and kills the session if he or she is logged in
     */
    public function logout(){
        //set this on starting page
        session_unset();
        session_destroy();
        unset($_SESSION);

        if (ini_get("session.use_cookies")){
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]);
        }
        //logout succeeded
        return true;
    }
    
    /**
     * Deletes the {@link User}<br>
     * id must be set
     * @return boolean
     */
    public function delete(){
        return $this->userDBC->deleteUser($this);
    }
    
    /**
     * Finds any {@link Participant} in relation to the {@link User}
     * @return boolean|User
     */
    public function findParticipants(){
        $user = $this->userDBC->findUserById($this->id);
        if(!$user){
            return false;
        }
        $participants = $this->userDBC->findParticipants($user);
        $user->setParticipants($participants);
        return $user;
    }
    
    /**
     * Changes and updates the role of the {@link User}
     * @return boolean
     */
    public function changeRole(){
        $user = $this->userDBC->findUserById($this->getId());
        if($user->getRole() == "user"){
            $this->setRole("admin");
        }else if($user->getRole() == "admin"){
            if(!($this->userDBC->checkLastAdmin($user))){
                return false;
            }
            $this->setRole("user");
        }
        $result = $this->userDBC->updateRole($this);
        if($result){
            $_SESSION['role'] = $this->getRole();
        }
        return $result;
    }
    
    /**
     * Books a {@link Trip}
     * @param Trip $trip
     * @param Insurance $insurance
     * @return boolean|int
     */
    public function bookTrip($trip, $insurance){
        $insuranceId = null;
        if($insurance){
            $insurances = $this->userDBC->getInsurances();
            $insuranceInstance = $insurances[0];//this assumes that there is just one Insurance to consider
            $insuranceId = $insuranceInstance->getId();
        }
        return $this->userDBC->insertBooking($this, $trip, $insuranceId);
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
    
    public function getGender()
    {
        return $this->gender;
    }
    public function getStreet() {
        return $this->street;
    }

    public function getZipCode() {
        return $this->zipCode;
    }

    public function getLocation() {
        return $this->location;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getRole() {
        return $this->role;
    }

    public function getBirthDate() {
        return $this->birthDate;
    }

    public function getPassword() {
        return $this->password;
    }
    
    public function getLastBooking(){
        return $this->lastBooking;
    }

    public function setId($id) {
        /* @var $id type int*/
        $this->id = (int) $id;
    }
    
    public function getParticipants(){
        return $this->participants;
    }

    public function setFirstName($firstName) {
        $this->firstName = $firstName;
    }

    public function setLastName($lastName) {
        $this->lastName = $lastName;
    }
    
    public function setGender($gender){
        $this->gender = $gender;
    }

    public function setStreet($street) {
        $this->street = $street;
    }

    public function setZipCode($zipCode) {
        /* @var $zipCode type int*/
        $this->zipCode = (int) $zipCode;
    }

    public function setLocation($location) {
        $this->location = $location;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function setRole($role) {
        $this->role = $role;
    }

    public function setBirthDate($birthDate) {
        $this->birthDate = $birthDate;
    }

    public function setPassword($password) {
        $this->password = $password;
    }
    
    public function setLastBooking($lastBooking){
        $this->lastBooking = $lastBooking;
    }
    
    public function setParticipants($participants){
        $this->participants = $participants;
    }

}
