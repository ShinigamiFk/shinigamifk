<?php

/*
  |--------------------------------------------------------------------------
  | Shinigami Framework
  |--------------------------------------------------------------------------
 */

/**
 * Path start site.
 * 
 * @global string SITE
 */
define('SITE', '/shinigamifk');

/**
 * Separator
 * 
 * @global string DS
 */
define('DS', '/');

/**
 * Absolute path of the project.
 * 
 * @global string APP_ROOT
 */
define('APP_ROOT', str_replace("\\", '/', realpath(dirname(__FILE__)) . DS));

/**
 * Absolute path to the directory of the application framework.
 * 
 * @global string APP_PATH
 */
define('APP_PATH', APP_ROOT . 'app' . DS);

/**
 * Absolute path to the core of the framework directory.
 * 
 * @global string APP_PATH
 */
define('CORE_PATH', APP_ROOT . 'core' . DS);

/**
 * Port on which the application is listening.
 * 
 * @global int PORT
 */
define('PORT', 80);

/**
 * Path site domain.
 * 
 * @global string BASE_URL
 */
define('BASE_URL', 'http://' . $_SERVER['SERVER_NAME'] . ":" . PORT . SITE);

//include smarty
require_once APP_ROOT . 'libraries/smarty/Smarty.class.php';

//include config framework
require_once CORE_PATH . 'autoload.php';
require_once CORE_PATH . 'config/constants.php';
require_once CORE_PATH . 'config/dispatcher.php';
require_once CORE_PATH . 'mongo/modelMongo.php';
require_once CORE_PATH . 'mongo/mongodbFactory.php';
require_once CORE_PATH . 'controller.php';
require_once CORE_PATH . 'view.php';
require_once CORE_PATH . 'session.php';

//include config proyect
require_once APP_ROOT . 'config/constants.php';
require_once APP_ROOT . 'config/mongo/database.php';
require_once APP_ROOT . 'config/controllers/baseController.php';

//include propel
require_once APP_ROOT . 'config/propel/propel.php';

//dispatcher
$dispatcher = new Dispatcher();
$dispatcher->dispatch();
