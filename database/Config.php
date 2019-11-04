<?php
namespace database;
/**
 * Provides access to the database and email settings
 * <ul>
 * <li>local through config.env</li>
 * <li>on cloud through the $_ENV['variable'] injection</li>
 * </ul>
 * @author andreas.martin
 */
class Config
{
    protected static $iniFile = "database/config.env";
    protected static $config = [];

    /**
     * Initializes the database variables
     * <ul>
     * <li>searches local for the config.env</li>
     * <li>if config.env does not exists (e.g. on cloud), then load the variables from {@link loadENV()}</li>
     * </ul>
     */
    public static function init()
    {
        if (file_exists(self::$iniFile)) {
            self::$config = parse_ini_file(self::$iniFile);
        } else if (file_exists("../". self::$iniFile)) {
            self::$config = parse_ini_file("../". self::$iniFile);
        } else {
            self::loadENV();
        }
    }

    /**
     * Gets the variables with the specified key
     * @param String $key
     * @return String
     */
    public static function get($key)
    {   
        if (empty(self::$config)){
            self::init();
        }
        return self::$config[$key];
    }

    /**
     * Loads the settings from the injected $_ENV from the cloud provider
     */
    private static function loadENV(){        
        if (isset($_ENV["JAWSDB_MARIA_URL"])) {
            $dbopts = parse_url($_ENV["JAWSDB_MARIA_URL"]);
            self::$config["database.user"] = $dbopts["user"];
            self::$config["database.host"] = $dbopts["host"];
            self::$config["database.port"] = $dbopts["port"];
            self::$config["database.name"] = ltrim($dbopts["path"], '/');   
            self::$config["database.password"] = $dbopts["pass"];
        }
        if(isset($_ENV["SENDGRID_API_KEY"])){
            self::$config["sendGrid.value"] = $_ENV["SENDGRID_API_KEY"];
        }
    }
}