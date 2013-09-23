<?php
error_reporting(E_ALL|E_STRICT);
ini_set('display_errors', '0');
ini_set('error_log', 'errors.log');
date_default_timezone_set('UTC');



/**
* Welcome to Servant
*
* This script is where we route all dynamic requests to. It creates an instance of Servant and runs it to serve a response.
*/
class Index {

	/**
	* Run a program cleanly with globals gone
	*/
	public function __construct () {
		if (isset($_SERVER, $_COOKIE, $_POST, $_GET, $_REQUEST, $_FILES)) {

			// Arbitrary preparations
			$arguments = func_get_args();
			$preparation = call_user_func_array(array($this, 'prepare'), $arguments);

			// Get rid of hazardous globals
			unset($_SERVER, $_COOKIE, $_POST, $_GET, $_REQUEST, $_FILES);

			// Run program
			call_user_func_array(array($this, 'run'), $preparation);
		}
	}

	/**
	* Prepare for the program
	*/
	private function prepare ($errorHandlerFile, $helpersDirectory, $classesDirectory, $pathsFile, $constantsDirectory) {

		// Errors
		require $errorHandlerFile;

		// Load helpers
		foreach (glob($helpersDirectory.'*.php') as $path) {
			require_once $path;
		}
		unset($path);

		// Load servant classes
		foreach (rglob_files($classesDirectory, 'php') as $path) {
			require_once $path;
		}
		unset($path);



		// Paths
		$paths = array();
		require $pathsFile;

		// JSON settings
		// FLAG I should keep these settings as PHP or parse the JSON in ServantSettings (things go FUBAR if JSON parsing fails here)
		$constants = array();
		foreach (rglob_files($constantsDirectory, 'json') as $path) {
			$constants = array_merge($constants, json_decode(file_get_contents($path), true));
		}
		unset($path);
		unset($includes);

		// User input
		$input = $_GET;



		// These will be passed to the runner
		return array($paths, $constants, $input);
	}



	/**
	* Run the program
	*/
	private function run ($paths, $constants, $input) {

		// Start and run a Servant instance
		$servant = new ServantMain();
		$servant->init($paths, $constants, $input);
		$servant->run();

	}

}

new Index(
	'includes/errors.php',
	'includes/helpers/',
	'includes/classes/',
	'includes/paths.php',
	'includes/constants/'
);
die();

?>