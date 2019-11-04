<?php

namespace helpers;

/**
 * Computes the margins of the {@link Trip} and {@link Insurance} to provide single point of truth to margins
 *
 * @author Lukas
 */
class Margin {
    
    private static $insuranceMargin = 1.1;
    private static $tripMargin = 1.2;
    
    /**
     * Computes {@link Insurance} margin of 10%
     * @param type $money
     * @return type
     */
    public static function addInsurance($money){
        return $money * self::$insuranceMargin;
    }
    
    /**
     * Computes {@link Trip} margin of 20%
     * @param type $money
     * @return type
     */
    public static function addTrip($money){
        return $money * self::$tripMargin;
    }
    
}
