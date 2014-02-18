<?php

/**
 * Dynamically loads the classes used in the execution of the script.
 * 
 * @param String $class
 */
spl_autoload_register('__autoload');

function __autoload($class) {

    $class = lcfirst($class);
    
    $path = APP_PATH . "controllers/$class.php";
    if (file_exists($path)) {
        require_once $path;
    }
    
    $path = APP_ROOT . "libraries/helpers/$class.php";
    if (file_exists($path)) {
        require_once $path;
    }
    
    $path = APP_PATH . "models/$class.php";
    if (file_exists($path)) {
        require_once $path;
    }
    
}