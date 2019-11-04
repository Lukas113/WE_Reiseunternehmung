<?php
namespace database;
use database\Config;
use \mysqli;

/**
 * This class provides an access to the database through the singletion pattern
 * @author Lukas
 */
class DBConnection {
    
    protected static $mysqliInstance = null;
    
    /**
     * Initializes the mysqliInstance
     */
    public function __construct() {
        $host = Config::get("database.host");
        $user = Config::get("database.user");
        $password = Config::get("database.password");
        $dbname = Config::get("database.name");
            
        self::$mysqliInstance = new mysqli($host, $user, $password, $dbname);
        
        /* check connection */
        if (self::$mysqliInstance->connect_error) {
            printf("Connect failed: %s\n", self::$mysqliInstance->connect_error);
            exit();
        }
    }

    
    /**
     * Gets an instance of DBConnection (singleton pattern)
     * @return DBConnection
     */
    public static function connect()
    {
        if (self::$mysqliInstance) {
            return self::$mysqliInstance;
        }

        new self();

        return self::$mysqliInstance;
    }
}
