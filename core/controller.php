<?php

class Controller {

    /**
     * Object View
     * 
     * @var View 
     */
    protected $_view;

    /**
     * Object Sesion
     *
     * @var Session $_sesion 
     */
    protected $_sesion;

    /**
     * Save the information in the array $ _POST
     * 
     * @var array $_helper
     */
    protected $post;

    /**
     * Construct
     */
    public function __construct() {
        $route = Dispatcher::getRoute();
        $this->_view = new View($route);
        $this->_sesion = Session::singleton();
        $this->post = $_POST;
    }

    /**
     * Imports the requested file
     * 
     * @param String $path_file
     * @throws LibraryNotFound
     */
    protected function loadLibrary($path_file) {
        $pathLibrary = APP_ROOT . 'libraries/' . $path_file . '.php';
        if (is_readable($pathLibrary)) {
            require_once $pathLibrary;
        } else {
            throw new Exception("Library $path_file not found.");
        }
    }

    /**
     * Clean the strange characters of data stored in the array $ _POST 
     * The position passed by parameter.
     * 
     * @param String $key
     * @return String|Array|Null
     */
    static function post($key) {
        if (isset($_POST[$key])) {
            if (is_string($_POST[$key])) {
                $_POST[$key] = str_replace("'", "&#39;", $_POST[$key]);
                return trim(strip_tags($_POST[$key]));
            } elseif (is_array($_POST[$key])) {
                return $_POST[$key];
            }
        }
        return null;
    }

    /**
     * Clean the strange characters of data stored in the array $ _GET 
     * The position passed by parameter.
     * @param String $key
     * @return String|Null
     */
    static function get($key) {
        return (isset($_GET[$key])) ? trim(strip_tags($_GET[$key])) : null;
    }

    /**
     * Determines whether the request was made via AJAX.
     * 
     * @return Boolean
     */
    public function isAJAX() {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }

}