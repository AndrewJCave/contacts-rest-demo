<?php

/**
 * Description of DBAccess
 *
 * @author ajcave
 * @package Core DataLayer
 */
class DBAccess {

    private $dbParams;

    //put your code here
    
    /**
     * Mysql connection
     *
     * @var resource
     */
    protected static $_db;
    
    
    function __construct($dbParams = null) {
       //echo "start \r\n";
        if ($dbParams !== null) {
            self::$_db = self::getDbConn($dbParams);
        } else if (!self::$_db) {
            self::$_db = self::getDbConn();
        }
        
        self::getDbConn();
    }
    
    static function getDBConn() {
        if (self::$_db) {
            return self::$_db;
        } else {
            $host = API_DB_HOST;
            $port = API_DB_PORT;
            $username = API_DB_USERNAME;
            $password = API_DB_PASSWORD;
            $dbName = API_DB_DBNAME;
            
            $dsn = 'mysql:dbname=' . $dbName . ';host=' . $host . ';port=' . $port;
            
            try {
                self::$_db = new PDO($dsn,$username,$password);
            } catch (Exception $ex) {
                die('Could not connect to database: ' . $ex);
            }
        }
    }
    
    private function isConnected() {
        return self::$_db->getAttribute(PDO::ATTR_CONNECTION_STATUS);
    }
    
    
    public function getTableNames() {
        //self::$_db->
        $sql = "SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_TYPE='BASE TABLE' and TABLE_SCHEMA='" . API_DB_DBNAME . "'";
        
        if ($this->isConnected()) {
            $statement = $this->getDBConn()->prepare($sql);
            $statement->execute();
            $rs = $statement->fetchAll(PDO::FETCH_ASSOC);
                    
            return $rs;
        }     
    }

    /**
     * Execute the provided sql query
     * @param type $query
     * @return boolean or result set
     */
    public function executeQuery($query = null) {
        //self::$_db->
        if ($query === null) {
            return false;
        }
        $sql = $query;
        
        if ($this->isConnected()) {
            $statement = $this->getDBConn()->prepare($sql);
            $statement->execute();
            $rs = $statement->fetchAll(PDO::FETCH_ASSOC);
                    
            return $rs;
        }     
    }

}
