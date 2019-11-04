<?php

namespace helpers;

/**
 * Provides the default paths for the images and pdfs to provide single point of truth:
 * <ul>
 * <li>{@link DefaultPath::getBus()}</li>
 * <li>{@link DefaultPath::getTripTemplate()}</li>
 * <li>{@link DefaultPath::getTrip()}</li>
 * <li>{@link DefaultPath::getDayprogram()}</li>
 * <li>{@link DefaultPath::getHotel()}</li>
 * <li>{@link DefaultPath::getInvoice}</li>
 * <li>{@link DefaultPath::getInvoiceFileName()}</li>
 * </ul>
 *
 * @author Lukas
 */
class DefaultPath {
    
    private static $imgPath = "views/assets/img/default";
    private static $pdfPath = "views/assets/pdfs/default";
    
    public static function getBus(){
        return self::$imgPath."Bus.jpg";
    }
    
    public static function getTripTemplate(){
        return self::$imgPath."Trip.jpg";
    }
    
    public static function getTrip(){
        return self::$imgPath."Trip.jpg";
    }
    
    public static function getDayprogram(){
        return self::$imgPath."Dayprogram.jpg";
    }
    
    public static function getHotel(){
        return self::$imgPath."Hotel.jpg";
    }
    
    public static function getInvoice(){
        return self::$pdfPath."Invoice.pdf";
    }
    
    public static function getInvoiceFileName(){
        $expl = explode("/", self::$pdfPath);
        return end($expl);

    }
    
}
