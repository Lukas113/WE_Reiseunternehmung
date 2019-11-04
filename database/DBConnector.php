<?php

namespace database;

use \mysqli;
use database\DBConnection;

/**
 * Superclass of all database connectors to provide access to
 * <ul>
 * <li>singleton pattern of {@link DBConnection}</li>
 * <li>{@link executeInsert($stmt)} to easy execute inserts and updates</li>
 * <li>{@link executeDelete($stmt)} to easy execute deletions</li>
 * </ul>
 * @author Lukas
 */
class DBConnector {
    
    protected $mysqliInstance;
    
    /**
     * Constructor to access singleton database connection
     * @param mysqli $mysqliInstance
     */
    public function __construct(mysqli $mysqliInstance = null) {
        if(is_null($mysqliInstance)){
            $this->mysqliInstance = DBConnection::connect();
        } else {
            $this->mysqliInstance = $mysqliInstance;
        }
    }
    
    /**
     * Helper function to perform an insert query
     * @param stmt $stmt
     * @return boolean|int
     */
    protected function executeInsert($stmt){
        $success = $stmt->execute();
        $id = $stmt->insert_id;
        $stmt->close();
        if($success){
            return $id;
        }
        return false;
    }
    
    /**
     * Helper function to perform a delete query
     * @param stmt $stmt
     * @return boolean
     */
    protected function executeDelete($stmt){
        $success = $stmt->execute();
        $stmt->close();
        if($success){
            return true;
        }
        return false;
    }
    
}
