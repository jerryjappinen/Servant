<?php

/**
* IndexWrapper
*
* This is a generic wrapper that
* 	- takes in the request details provided by PHP
* 	- clears dangerous globals
* 	- runs Servant (the main program)
* 	- uses Servant to serve a response to or redirect the request.
*/
class IndexWrapper {

	// Wrapper variables
	private $constantsJson 	= '{}';
	private $debug 			= false;
	private $input 			= array();
	private $paths 			= array();



	/**
	* Basic flow of this wrapper (only runs if global variables aren't cleared)
	*/
	public function __construct () {
		if (isset($_SERVER, $_COOKIE, $_POST, $_GET, $_REQUEST, $_FILES)) {

			// Preparations
			$arguments = func_get_args();
			call_user_func_array(array($this, 'prepare'), $arguments);

			// Get rid of hazardous globals
			// unset($_SERVER);
			unset($_COOKIE, $_POST, $_GET, $_REQUEST, $_FILES);

			// Run program
			call_user_func(array($this, 'run'));
			return $this;

		}
	}



	/**
	* Prepare parameters for program's initialization before superglobals are cleared
	*/
	private function prepare ($debugHandlerFile, $errorHandlerFile, $helpersDirectory, $classesDirectory, $pathsFile, $constantsDirectory) {

		// Debug mode & errors
		require $debugHandlerFile;
		require $errorHandlerFile;



		// Load helpers
		foreach (glob($helpersDirectory.'*.php') as $path) {
			require_once $path;
		}

		// Load program classes
		foreach (rglob_files($classesDirectory, 'php') as $path) {
			require_once $path;
		}



		// Paths
		require $pathsFile;

		// JSON settings
		$jsons = array();
		foreach (rglob_files($constantsDirectory, 'json') as $path) {
			$jsons[] = unsuffix(unprefix(trim(file_get_contents($path)), '{'), '}');
		}
		$this->constantsJson = '{'.implode(',', $jsons).'}';

		// User input
		$this->input = array(
			'get' => $_GET,
			'post' => $_POST,
			'put' => array(),
			'delete' => array(),
			'files' => $_FILES,
		);



		return $this;
	}



	/**
	* Run the program
	*/
	private function run () {

		// Start and run the main program
		$servant = create_object('ServantMain');
		$servant->init($this->paths, $this->constantsJson, ($this->debug ? true : false));
		$servant->setup();

		// Input and response (last input item overrides preceding ones)
		$i = $this->input;
		$response = $servant->response(
			$i['files'],
			$i['delete'],
			$i['put'],
			$i['post'],
			$i['get']
		);

		// Answer request
		return $servant->serve($response);

	}

}

?>