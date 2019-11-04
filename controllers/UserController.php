<?php

namespace controllers;

use entities\User;
use entities\Participant;
use database\UserDBC;
use helpers\Validation;
use views\LayoutRendering;
use views\TemplateView;

/**
 * Controls the access to the functionalities of the {@link User}<br>
 * <ul>
 * <li>{@link register()}</li>
 * <li>{@link login()}</li>
 * <li>{@link logout()}</li>
 * <li>{@link getAllUsers())}</li>
 * <li>{@link deleteUser($userId)}</li>
 * <li>{@link deleteSelf()}</li>
 * <li>{@link createParticipant())}</li>
 * <li>{@link deleteParticipant($participantId}</li>
 * <li>{@link getParticipants()}</li>
 * <li>{@link changeRole($userId)}</li>
 * <li>{@link getHomepage()}</li>
 * </ul>
 * @author Lukas
 */
class UserController {
    
    /**
     * Registration process of a new {@link User}
     * @return boolean|int
     */
    public static function register(){
        $user = new User();
        $user->setFirstName(\filter_input(\INPUT_POST, 'firstName', \FILTER_SANITIZE_STRING));
        $user->setLastName(\filter_input(\INPUT_POST, 'lastName', \FILTER_SANITIZE_STRING));
        $user->setGender(\filter_input(\INPUT_POST, 'gender', \FILTER_SANITIZE_STRING));
        $user->setStreet(\filter_input(\INPUT_POST, 'street', \FILTER_SANITIZE_STRING));
        $zipCode = Validation::zipCode(\filter_input(\INPUT_POST, 'zipCode', \FILTER_VALIDATE_INT));
        if(!$zipCode){
            return false;
        }
        $user->setZipCode($zipCode);
        $user->setLocation(\filter_input(\INPUT_POST, 'location', \FILTER_SANITIZE_STRING));
        $email = filter_input(\INPUT_POST, 'email', \FILTER_VALIDATE_EMAIL);
        if(!$email){
            return false;
        }
        $user->setEmail($email);
        $birthDate = Validation::date(\filter_input(\INPUT_POST, 'birthDate', \FILTER_SANITIZE_STRING));
        if(!$birthDate){
            return false;
        }
        $user->setBirthDate($birthDate);
        $user->setPassword(\filter_input(\INPUT_POST, 'password', \FILTER_SANITIZE_STRING));
        //default role user (not admin)
        $user->setRole("user");
        
        $success = $user->register();
        if($success){
            return $success;
        }
        return false;
    }
    
    /**
     * Login process of a {@link User}
     * @return boolean|int
     */
    public static function login(){
        $user = new User();
        $email = \filter_input(\INPUT_POST, 'email', \FILTER_VALIDATE_EMAIL);
        if(!$email){
            return false;
        }
        $user->setEmail($email);
        $user->setPassword(\filter_input(\INPUT_POST, 'password', \FILTER_SANITIZE_STRING));
        
        $success = $user->login();
        if($success){
            return $success;
        }
        return false;
    }
    
    /**
     * Logout process of a {@link User}
     * @return boolean
     */
    public static function logout(){
        $user = new User();
        
        $success = $user->logout();
        if($success){
            return $success;
        }
        return false;
    }
    
    /**
     * Provides the overview of all {@link User}
     */
    public static function getAllUsers(){
        if(isset($_SESSION['role']) and $_SESSION['role'] == "admin"){
            $userDBC = new UserDBC();
            $users = $userDBC->getAllUsers();
            
            $adminUsers = new TemplateView("adminUsers.php");
            $adminUsers->users = $users;
            LayoutRendering::basicLayout($adminUsers);
            return true;
        }else{
            return false;
        }
    }
    
    
    /**
     * Deletes a {@link User} with the specified id
     * @param int $userId
     * @return boolean
     */
    public static function deleteUser($userId){
        if(isset($_SESSION['role']) and $_SESSION['role'] == "admin"){
            $user = new User();
            $id = Validation::positiveInt($userId);
            if(!$id){
                return false;
            }
            $user->setId($id);
            $success = $user->delete();
            if($success){
                return $success;
            }
            return false;
        }else{
            return false;
        }
    }
    
    /**
     * Deletes the account itself of the {@link User}
     * @return boolean
     */
    public static function deleteSelf(){
        $user = new User();
        $user->setId($_SESSION['userId']);
        $success = $user->delete();
        if($success){
            return $success;
        }
        return false;
        //not in use yet
    }
    
    /**
     * Creates a new {@link Participant} according to the {@link User} to make them ready for {@link Trip} booking
     * @return boolean|int
     */
    public static function createParticipant(){
        $participant = new Participant();
        
        $participant->setFirstName(\filter_input(\INPUT_POST, 'firstName', \FILTER_SANITIZE_STRING));
        $participant->setLastName(\filter_input(\INPUT_POST, 'lastName', \FILTER_SANITIZE_STRING));
        $birthDate = Validation::date(\filter_input(\INPUT_POST, 'birthDate', \FILTER_SANITIZE_STRING));
        if(!$birthDate){
            return false;
        }
        $participant->setBirthDate($birthDate);
        $participant->setFkUserId($_SESSION['userId']);
        
        $success = $participant->create();
        if($success){
            return $success;
        }
        return false;
    }
    
    /**
     * Deletes a {@link Participant} by the given id
     * @param int $participantId
     * @return boolean
     */
    public static function deleteParticipant($participantId){
        $participant = new Participant();
        
        $id = Validation::positiveInt($participantId);
        if(!$id){
            return false;
        }
        $participant->setId($id);
        
        $success = $participant->delete();
        if(!$success){
            return true;
        }
        return false;
    }
    
    /**
     * Provides an overview of a {@link User} with the related {@link Participants}
     * @return boolean
     */
    public static function getParticipants(){
        if(!isset($_SESSION['role']) or (isset($_SESSION['role']) and $_SESSION['role'] != "user")){
            return false;
        }
        $user = new User();

        $user->setId($_SESSION['userId']);
            
        $travelersView = new TemplateView("addTravelers.php");
        $travelersView->participants = $user->findParticipants()->getParticipants();
        LayoutRendering::basicLayout($travelersView, "headerUserLoggedIn");
        return true;
    }
    
    /**
     * Changes teh role of the specified {@link User} to allow to make someone to an admin or user
     * @param int $userId
     * @return boolean
     */
    public static function changeRole($userId){
        if(!isset($_SESSION['role']) or (isset($_SESSION['role']) and $_SESSION['role'] != "admin")){
            return false;
        }
        $user = new User();
        $id = Validation::positiveInt($userId);
        if(!$id){
            return false;
        }
        $user->setId($id);
        
        $success = $user->changeRole();
        if($success){
            //no change clientside if success
            return $success;
        }
        return false;
    }
    
    /**
     * Provides view of the start-side (after login) of a admin or user
     * @return boolean
     */
    public static function getHomepage(){
        if(isset($_SESSION['role']) and $_SESSION['role'] == "admin"){
            $homepage = new TemplateView("adminMain.php");
            LayoutRendering::basicLayout($homepage);
            return true;
        }else if(isset($_SESSION['role']) and $_SESSION['role'] == "user"){
            $homepage = new TemplateView("homepage.php");
            LayoutRendering::basicLayout($homepage, "headerUserLoggedIn");
            return true;
        }else{
            return false;
        }
    }
}
