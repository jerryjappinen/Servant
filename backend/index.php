<?php
error_reporting(0);
ini_set('display_errors', '0');
ini_set('error_log', 'errors.log');
mb_internal_encoding('UTF-8');
date_default_timezone_set('UTC');



/**
* Welcome
*
* This script is where we route all dynamic requests to.
*
* We run Servant via a wrapper class that clears dangerous globals and includes core classes.
* 
* After that, Servant is ready to be used to serve responses for requests.
*/
require_once 'IndexWrapper.php';
session_start();
new IndexWrapper(
	'core/debug.php',
	'core/errors.php',
	'core/helpers/',
	'core/classes/',
	'paths.php',
	'constants/'
);
die();

?>