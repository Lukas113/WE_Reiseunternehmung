<?php

namespace controllers;

use database\UserDBC;
use entities\User;
use mail\EmailServiceClient;


/**
 * Ajax helper
 * Contains 2 methods. A method which checks if the email already exists and a method which checks if the login data is correct.
 *
  * @author Vanessa Cajochen
 */
class AjaxController {
    
    // checks if the email already exists
    public static function checkEmail(){
        
        $email = filter_input(\INPUT_POST, 'email', \FILTER_VALIDATE_EMAIL);
        if(!$email){
            return false;
        }
        $user = new UserDBC();
        header('Content-type: application/json');
        if($user->checkByEmail($email)){
            $response_array['status'] = 'error';  
        }else {
            $response_array['status'] = 'success';  
        }
        echo json_encode($response_array);
        return true;
     }
      
     
     // checks if the login data is correct
     public static function checkLogin(){     
         
        $email = filter_input(\INPUT_POST, 'email', \FILTER_VALIDATE_EMAIL);
        if(!$email){
            return false;
        }        
        $user = new User();
        header('Content-type: application/json');
                 
        $user->setEmail($email);
        $user->setPassword(\filter_input(\INPUT_POST, 'password', \FILTER_SANITIZE_STRING));
                       
        if($user->loginPreCheck()){
            $response_array['status'] = 'success';  
        }else {
            $response_array['status'] = 'error';          
        }
        echo json_encode($response_array);
        return true;  
     }     
}