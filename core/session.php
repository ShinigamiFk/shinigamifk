<?php

class Session {

    /**
     * Single instance of the class.
     * 
     * @var Session $_instance
     */
    private static $_instance;

    /**
     * ID of the session
     *
     * @var string $_sessionID
     */
    public static $_sessionID;

    /**
     * Name of the session.
     * 
     * @var String $_sessionName
     */
    public static $_sessionName;

    private function __construct() {
        @session_start();
        self::$_sessionID = session_id();
        self::$_sessionName = 'KEY';
    }

    /**
     * Gets the instance of session
     * 
     * @return Session
     */
    public static function singleton() {
        if (!isset(self::$_instance)) {
            $className = __CLASS__;
            self::$_instance = new $className;
        }
        return self::$_instance;
    }

    /**
     * Returns the value of a variable Session
     * 
     * @param string $var
     * @return string
     */
    public function __get($var) {
        if (isset($_SESSION['KEY'][sha1($var)])) {
            return $_SESSION['KEY'][sha1($var)];
        }
        return null;
    }

    /**
     * Create a variable Session
     * 
     * @param string $var name of the session variable
     * @param string $val value of the session variable
     * @return type
     */
    public function __set($var, $val) {
        if (!empty($var)) {
            return $_SESSION['KEY'][sha1($var)] = $val;
        }
    }

    /**
     * Destroy the session, eliminating all variables  SESSION
     * 
     */
    public function __destroy() {
        foreach ($_SESSION['KEY'] as $var => $val) {
            $_SESSION['KEY'][$var] = null;
        }
        session_destroy();
    }

    /**
     * Remove a session variable.
     * 
     * @param String $var Nombre de la variable
     * @return boolean 
     */
    public function __unset($var) {
        if (isset($_SESSION['KEY'][sha1($var)])) {
            unset($_SESSION['KEY'][sha1($var)]);
            return true;
        }
        return false;
    }

    /**
     * Destroy the session
     */
    public function __destruct() {
        session_write_close();
    }

}