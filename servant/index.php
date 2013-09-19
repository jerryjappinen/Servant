<?php

/**
* Welcome to Servant
*
* This script is where we route all requests to. It creates a Servant instance and runs it to serve a response.
*/



/**
* Error handling & debug features
*/
error_reporting(E_ALL|E_STRICT);
ini_set('display_errors', '0');
ini_set('error_log', 'errors.log');
date_default_timezone_set('UTC');

// Debug features on localhost
if (in_array($_SERVER['REMOTE_ADDR'], array('127.0.0.1', '::1'))) {
	ini_set('display_errors', '1');
	error_reporting(error_reporting() & ~E_NOTICE);

// Last resort error handling in production
} else {

	// Error handler functions
	set_error_handler('handleFubarError');
	set_exception_handler('handleFubarException');
	function handleFubarError ($errno, $errstr) {
		return handleFubar($errno, $errstr);
	}
	function handleFubarException ($exception) {
		return handleFubar($exception->getCode(), $exception->getMessage());
	}
	function handleFubar ($code = 500, $message = '') {
		header('HTTP/1.1 500 Internal Server Error');
		header('Content-Type: text/html; charset=utf-8');
		echo '
		<html>
			<head>
				<title>Server error :(</title>
				<style type="text/css">
					body {
						background-color: #0a74a5;
						color: #fff;
						font-family: sans-serif;
						padding: 10%;
						max-width: 50em;
						margin: 0 auto;
						font-weight: 200;
					}
					h1 {
						font-weight: 200;
						font-size: 2.6em;
					}
				</style>
			</head>
			<body>
				<h1>Something went wrong :(</h1>
				<p>We\'ve been notified now, and will fix this as soon as possible.</p>
			</body>
		</html>
		';

		die();
		return false;
	}

}




/**
* Includes
*/

// Paths
$includePaths = array(
	'paths' => 'includes/paths.php',
	'helpers' => 'includes/helpers/',
	'classes' => 'includes/classes/',
	'settings' => 'includes/settings/',
);
require $includePaths['paths'];

// Helpers
foreach (glob($includePaths['helpers'].'*.php') as $path) {
	require_once $path;
}

// Servant classes
foreach (rglob_files($includePaths['classes'], 'php') as $path) {
	require_once $path;
}
unset($path);



// JSON settings
// FLAG I should keep these settings as PHP or parse the JSON in ServantSettings (things go FUBAR if JSON parsing fails here)
$settings = array();
foreach (rglob_files($includePaths['settings'], 'json') as $path) {
	$settings = array_merge($settings, json_decode(file_get_contents($path), true));
}
unset($path);
unset($includePaths);



/**
* Running Servant
*/

// Get rid of hazardous globals
$input = $_GET;
unset($_SERVER, $_COOKIE, $_POST, $_GET, $_REQUEST, $_FILES);

// Startup
$servant = new ServantMain();
$servant->init($paths, $settings, $input);
unset($paths, $settings, $input);
$servant->run();
die();
?>