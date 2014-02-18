<?php

/*
|--------------------------------------------------------------------------
|| Constants and project configurations.
|--------------------------------------------------------------------------
*/

/**
*  Establece cuáles errores de PHP son notificados.
*/
error_reporting(E_ALL ^ E_NOTICE);

/**
* Sets the default timezone used by all date functions / hour in a script.
*/
date_default_timezone_set('America/Bogota');

/**
* Layout using the default project.
* 
* @global string DEFAULT_LAYOUT
*/
define('DEFAULT_LAYOUT', 'default');

/**
* Controller uses the default project.
* 
* @global string DEFAULT_LAYOUT
*/
define('DEFAULT_CONTROLLER', 'index');