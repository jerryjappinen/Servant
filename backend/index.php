<?php
// Root of all problems
error_reporting(E_ALL|E_STRICT);
ini_set('display_errors', '1');
date_default_timezone_set('UTC');



// Treat paths
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
foreach ($paths as $key => $value) {
	if (substr($paths[$key], 0, 1) === '/') {
		$paths[$key] = substr($paths[$key], 1);
	}
	if (substr($paths[$key], -1) !== '/') {
		$paths[$key] = $paths[$key].'/';
	}
}
$paths['documentRoot'] = '/'.$paths['documentRoot'];



// Server-side code

// Helpers & classes
foreach (glob($paths['documentRoot'].$paths['root'].$paths['helpers'].'*.php') as $path) {
	require_once $path;
}
foreach (rglob_files($paths['documentRoot'].$paths['root'].$paths['classes'], 'php') as $path) {
	require_once $path;
}
foreach (rglob_files($paths['documentRoot'].$paths['root'].$paths['utilities'], 'php') as $path) {
	require_once $path;
}
unset($path);



// Include JSON settings
// FLAG I should throw errors when parsing JSON fails, but I don't know how to at this point
$settings = array();
foreach (rglob_files($paths['documentRoot'].$paths['root'].$paths['settings'], 'json') as $path) {
	$settings = array_merge($settings, json_decode(file_get_contents($path), true));
}
unset($path);



// Take input
$site = '';
if (isset($_GET['site']) and (is_string($_GET['site']) or is_int($_GET['site']))) {
	$site = $_GET['site'];
}
$article = array();
$i=0;
foreach (array('dir1', 'dir2', 'dir3', 'dir4', 'dir5', 'dir6', 'dir7', 'dir8') as $key) {
	if (isset($_GET[$key]) and (is_string($_GET[$key]) or is_int($_GET[$key]))) {
		$article[$i] = strval($_GET[$key]);
	} else {
		break;
	}
	$i++;
}
unset($i, $key);



// Clear things to prevent abuse
unset($_SERVER, $_COOKIE, $_GET, $_POST, $_REQUEST, $_FILES);

try {

	// Launch it
	$servant = new ServantMain();

	// Set up things
	$servant->initialize($paths, $settings)->select($site, $article);
	unset($paths, $settings, $site, $article);

	// Run it
	$servant->run();
	// echo 'debug.php';


} catch (Exception $e) {

	echo '
	<h1>'.$e->getCode().' :(</h1>
	<p>'.$e->getMessage().'</p>
	';

}

unset($path);

?>