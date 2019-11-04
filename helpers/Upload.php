<?php

namespace helpers;

/**
 * Provides upload of
 * <ul>
 * <li>images</li>
 * <li>pdfs</li>
 * </ul>
 *
 * @author Lukas
 */
class Upload {
    
    private static $imgPath = "views/assets/img/";
    private static $pdfPath = "views/assets/pdfs/";
    
    /**
     * Checks and stores an jpg, jpeg or png
     * @return boolean|String
     * 
     */
    public static function uploadImage(){
        
        $fileName = $_FILES['img']['name'];
        $fileTmpName = $_FILES['img']['tmp_name'];
        $fileSize = $_FILES['img']['size'];
        $fileError = $_FILES['img']['error'];
        $fileType = $_FILES['img']['type'];
        
        $fileExt = explode('.', $fileName);
        $fileActualExt = strtolower(end($fileExt));
        
        $allowed = array('jpg', 'jpeg', 'png');
        
        if(in_array($fileActualExt, $allowed)){
            if($fileError === 0){
                if($fileSize < 10000000){
                    $fileNameNew = self::$imgPath.$fileExt[0].str_replace(".", "", uniqid('', true)).".".$fileActualExt;
                    $fileDestination = $fileNameNew;
                    move_uploaded_file($fileTmpName, $fileDestination);
                    return $fileNameNew;
                }else{
                    return false;
                }
            }else{
                return false;
            }
        }else{
            return false;
        }
        
    }
    
    /**
     * Checks and stores an pdf
     * @return boolean|String
     * 
     */
    public static function uploadPdf(){
        
        $fileName = $_FILES['invoice']['name'];
        $fileTmpName = $_FILES['invoice']['tmp_name'];
        $fileSize = $_FILES['invoice']['size'];
        $fileError = $_FILES['invoice']['error'];
        $fileType = $_FILES['invoice']['type'];
        
        $fileExt = explode('.', $fileName);
        $fileActualExt = strtolower(end($fileExt));
        
        $allowed = array('pdf');
        
        if(in_array($fileActualExt, $allowed)){
            if($fileError === 0){
                if($fileSize < 10000000){
                    $fileNameNew = self::$pdfPath.$fileExt[0].str_replace(".", "", uniqid('', true)).".".$fileActualExt;
                    $fileDestination = $fileNameNew;
                    move_uploaded_file($fileTmpName, $fileDestination);
                    return $fileNameNew;
                }else{
                    return false;
                }
            }else{
                return false;
            }
        }else{
            return false;
        }
        
    }
    
}
