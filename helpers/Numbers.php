<?php

namespace helpers;

/**
 * Provides single point of truth to get:
 * <ul>
 * <li>{@link Numbers::getMinAllocation()}</li>
 * <li>{@link Numbers::getMaxAllocation()}</li>
 * <li>{@link Numbers::roundPrice($price)}</li>
 * </ul>
 *
 * @author Lukas
 */
class Numbers {
    
    private static $minAllocation = 12;
    private static $maxAllocation = 20;
    
    /**
     * minAllocation 12
     * @return int
     */
    public static function getMinAllocation(){
        return self::$minAllocation;
    }
    
    /**
     * maxAllocation 20
     * @return int
     */
    public static function getMaxAllocation(){
        return self::$maxAllocation;
    }
    
    /**
     * Rounds the given price to the nearest 0.5
     * @param double $price
     * @return double
     */
    public static function roundPrice($price){
        return round($price * 20, 0) / 20;
    }
    
}
