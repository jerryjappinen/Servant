<?php
// Root of all problems
error_reporting(E_ALL|E_STRICT);
ini_set('display_errors', '1');
date_default_timezone_set('UTC');



//
// Error handling
//

// Custom error handling functions
// set_error_handler('handleFubarError');
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
					background-color: #fafafa;
					color: #0d0d0d;
					font-family: sans-serif;
					padding: 5%;
					max-width: 50em;
					margin: 0 auto;
				}
			</style>
		</head>
		<body>
			<!-- Error code: '.$code.' -->
			<h1>Something went wrong :(</h1>
			<p>We\'ve been notified now, and will fix this as soon as possible.</p>
			'.((isset($message) and !empty($message)) ? '<p>'.$message.'</p>' : '').'
		</body>
	</html>
	';

	die();
	return false;
}



//
// Paths
//

// Set and treat paths
require 'paths.php';

// Auto setect document root
if (!isset($paths['documentRoot'])) {
	$paths['documentRoot'] = $_SERVER['DOCUMENT_ROOT'];
}

// Auto detect root
if (!isset($paths['root'])) {
	$paths['root'] = substr($_SERVER['SCRIPT_NAME'], 0, -(strlen($paths['index'].'index.php')));
	if (substr($paths['root'], 0, 1) == '/') {
		$paths['root'] = substr($paths['root'], 1);
	}
}

// Sanitize path formatting
foreach ($paths as $key => $path) {
	if (substr($paths[$key], 0, 1) === '/') {
		$paths[$key] = substr($paths[$key], 1);
	}
	if (substr($paths[$key], -1) !== '/') {
		$paths[$key] = $paths[$key].'/';
	}
}
$paths['documentRoot'] = '/'.$paths['documentRoot'];
unset($key, $path);



// Load helpers & classes
foreach (glob($paths['documentRoot'].$paths['root'].$paths['helpers'].'*.php') as $path) {
	require_once $path;
}
foreach (rglob_files($paths['documentRoot'].$paths['root'].$paths['classes'], 'php') as $path) {
	require_once $path;
}
unset($path);



//
// JSON settings
//

// Include JSON settings
// FLAG I should throw errors when parsing JSON fails, but I don't know how to at this point
$settings = array();
foreach (rglob_files($paths['documentRoot'].$paths['root'].$paths['settings'], 'json') as $path) {
	$settings = array_merge($settings, json_decode(file_get_contents($path), true));
}
unset($path);



//
// Execute -_-
//

// Clear some things to prevent abuse
$input = $_GET;
unset($_SERVER, $_COOKIE, $_POST, $_GET, $_REQUEST, $_FILES);

// Startup
$servant = create(new ServantMain)->init($paths, $settings, $input);
unset($paths, $settings, $input);

// Run Servant
$servant->execute();
die();
?>