<?php

/**
* Main paths for internal use
*/
$paths = array(

	// Uncomment to override auto detection
	// 'documentRoot' 	=> '/Users/jjappinen/Documents/Development/',
	// 'root' 			=> 'foo.net/www/',
	// 'host' 			=> 'http://foo.net/',
	'index' 		=> 'servant/',

	// User-facing stuff
	'cache' 		=> 'cache/',
	'site' 			=> 'site/',
	'theme' 		=> 'assets/',
	'template' 		=> 'template/',

	// Backend
	'actions' 		=> 'servant/actions/',
	'temp' 			=> 'servant/temp/',
	'utilities' 	=> 'servant/utilities/',

);



/**
* Automatic path detection
*/

// Document root
if (!isset($paths['documentRoot'])) {
	$paths['documentRoot'] = $_SERVER['DOCUMENT_ROOT'];
}

// Relative root
if (!isset($paths['root'])) {
	$paths['root'] = substr($_SERVER['SCRIPT_NAME'], 0, -(strlen($paths['index'].'index.php')));
	if (substr($paths['root'], 0, 1) == '/') {
		$paths['root'] = substr($paths['root'], 1);
	}
}

// Domain
// NOTE If you need to add port, use this:
//		.':'.$_SERVER['SERVER_PORT'])
if (!isset($paths['host'])) {
	$paths['host'] = strtolower(substr($_SERVER['SERVER_PROTOCOL'], 0, strpos($_SERVER['SERVER_PROTOCOL'], '/'))).'://'.$_SERVER['HTTP_HOST'].'/';
}

?>