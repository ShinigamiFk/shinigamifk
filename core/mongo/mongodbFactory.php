<?php

abstract class MongodbFactory {

    /**
     * Maintains connection with mongodb
     * 
     * @var MongoClient 
     */
    private static $db;

    /**
     * Gets the instance of the database manager for mongodb.
     * 
     * @return MongoClient
     */
    public static function getDBHandler() {
        if (!isset(self::$db)) {
            global $mongo_db;
            $dbname = $mongo_db['dbname'];
            try {
                $connection = new MongoClient("mongodb://{$mongo_db['host']}:{$mongo_db['port']}");
                $ret = $connection->$dbname;
            } catch (Exception $e) {
                die('MongodbFactory: ' . $e->getMessage());
            }
            return self::$db = $ret;
        }
        return self::$db;
    }

}