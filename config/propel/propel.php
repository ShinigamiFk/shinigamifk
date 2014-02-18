<?php

// Include the main Propel script
require_once APP_ROOT . 'config/propel/runtime/lib/Propel.php';

$filename = APP_ROOT . "app/models/conf/conf.php";

if (file_exists($filename)) {
    // Initialize Propel with the runtime configuration
    Propel::init($filename);
    // Add the generated 'classes' directory to the include path
    set_include_path(APP_ROOT . "app/models/classes" . PATH_SEPARATOR . get_include_path());
}


