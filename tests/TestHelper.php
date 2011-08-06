<?php
// start output buffering
//ob_start();

// set our app paths and environments
define('BASE_PATH', realpath(dirname(__FILE__) . '/../'));
define('APPLICATION_PATH', BASE_PATH . '/application');
define('TEST_PATH', BASE_PATH . '/tests');
define('APPLICATION_ENV', 'testing');

// Include path
set_include_path(
    '.'
    . PATH_SEPARATOR . BASE_PATH . '/library'
    . PATH_SEPARATOR . get_include_path()
);

// Set the default timezone !!!
date_default_timezone_set('Europe/Brussels');

// We wanna catch all errors en strict warnings
error_reporting(E_ALL|E_STRICT);

require_once 'Zend/Application.php';
$application = new Zend_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/configs/application.ini'
);

Zend_Session::$_unitTestEnabled = true;

$application->bootstrap();

