<?php

namespace helpers;

/**
 * Provides validation to avoid programming with corrupt data:
 * <ul>
 * <li>{@link positiveInt($number)}</li>
 * <li>{@link positivePrice($price)}</li>
 * <li>{@link date($date)}</li>
 * <li>{@link upToDate($date)}</li>
 * <li>{@link invoiceType($type)}</li>
 * <li>{@link zipCode($zipCode)}</li>
 * </ul>
 * @author Lukas
 */
class Validation {
    
    /**
     * Checks whether a given number is an integer and positive
     * @param int $number
     * @return boolean|int
     */
    public static function positiveInt($number){
        if(is_numeric($number)){
            $number = intval($number);
        }
        if(!is_int($number)){
            return false;
        }
        if(!($number < 0)){
            return $number;
        }else{
            return false;
        }
    }
    
    /**
     * Checks whether a given price is a double and positive
     * @param double $price
     * @return boolean|double
     */
    public static function positivePrice($price){
        if(is_numeric($price)){
            $price = doubleval($price);
        }
        if(!is_double($price)){
            return false;
        }
        if(!($price < 0)){
            return $price;
        }else{
            return false;
        }
    }
    
    /**
     * Checks whether a given date is in format YYYY-MM-DD
     * @param String $date
     * @return boolean|String
     */
    public static function date($date){
        $date = date_create($date);
        $newFormat = date_format($date, "Y-m-d");
        if(preg_match('/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/', $newFormat)){
            return $newFormat;
        }else{
            return false;
        }
    }
    
    /**
     * Checks whether a given date is in format YYYY-MM-DD and lies in the future to prevent e.g. booking a {@link Trip} in the past
     * @param String $date
     * @return boolean|String
     */
    public static function upToDate($date){
        $date = self::date($date);
        if(!$date){
            return false;
        }
        $today = \date("Y-m-d");
        if($date > $today){
            return $date;
        }
        return false;
    }
    
    /**
     * Checks if the type of the {@link Invoice} is valid
     * @param String $type allowed types:
     * <ul>
     * <li>hotel</li>
     * <li>bus</li>
     * <li>insurance</li>
     * </ul>
     * @return boolean|String
     */
    public static function invoiceType($type){
        $allowed = array("hotel", "bus", "insurance", "other");
        if(in_array(strtolower($type), $allowed)){
            return strtolower($type);
        }else{
            return false;
        }
    }
    
    /**
     * Checks whether a given zipCode is in int format and between 0 and 100000
     * @param int $zipCode
     * @return boolean|int
     */
    public static function zipCode($zipCode){
        if(is_numeric($zipCode) and $zipCode > 0 and $zipCode < 100000){
            return $zipCode;
        }else{
            return false;
        }
    }
    
}
