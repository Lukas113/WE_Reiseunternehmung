<?php

namespace controllers;

use views\TemplateView;
use views\LayoutRendering;


/**
 * Controls the authentication of a user and provides access to:
 * <ul>
 * <li>{@link AuthController::authenticate()}</li>
 * <li>{@link loginView()}</li>
 * <li>{@link registerView()}</li>
 * </ul>
 * @author Lukas
 */
class AuthController {
    
    /**
     * Checks whether the User is logged-in
     * @return boolean
     */
    public static function authenticate(){
        if (isset($_SESSION["login"])) {
            return true;
        }else{
            return false;
        }
    }
    
    /**
     * Provides the loginView
     */
    public static function loginView(){
        $homepage = new TemplateView("homepage.php");
        LayoutRendering::basicLayout($homepage, "headerLoggedOut");
    }
    
    /**
     * Provides the registerView
     */
    public static function registerView(){
        $homepage = new TemplateView("registration.php");
        LayoutRendering::basicLayout($homepage, "headerLoggedOut");
    }

}
