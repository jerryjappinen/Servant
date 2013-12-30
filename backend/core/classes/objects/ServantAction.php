<?php

/**
* An action
*
* DEPENDENCIES
*   ServantConstants 	-> defaults
*   ServantFiles 		-> read
*   ServantFormat 		-> path
*   ServantPaths 		-> actions
*/
class ServantAction extends ServantObject {

	/**
	* Properties
	*/
	protected $propertyCache 		= null;
	protected $propertyContentType 	= null;
	protected $propertyData 		= null;
	protected $propertyFiles 		= null;
	protected $propertyId 			= null;
	protected $propertyInput 		= null;
	protected $propertyIsSite 		= null;
	protected $propertyPath 		= null;
	protected $propertyOutput 		= null;
	protected $propertyStatus 		= null;



	/**
	* Initialization
	*
	* Defaults are set here, and can be overridden by action's code.
	*/
	public function initialize ($id, $input) {

		// Set ID and input upon initialization
		$this->setId($id);
		$this->setInput($input);

		// Defaults
		$contentType = $this->servant()->constants()->defaults('contentType');
		$status = $this->servant()->constants()->defaults('status');

		return $this->contentType($contentType)->status($status);
	}



	/**
	* Convenience
	*/

	/**
	* Run
	*
	* Run custom scripts from action's package cleanly
	*/
	public function run () {

		// Variables to pass to action scripts
		$scriptVariables = array(
			'servant' => $this->servant(),
			'input' => $this->input(),
			'action' => $this,
		);

		// Run the scripts (NOTE that the output is not used)
		$this->servant()->files()->read($this->files('server'), $scriptVariables);

		return $this;
	}

	/**
	* Generate a child action
	*/
	public function nest ($id) {

		// No new input, use parent action's
		if (func_num_args() < 2) {
			$input = $this->input();

		} else {
			$arguments = func_get_args();
			array_shift($arguments);

			// Input object passed
			if ($this->getServantClass($arguments[0]) === 'input') {
				$input = $arguments[0];

			// New input
			} else {
				$input = call_user_func_array(array($this->servant()->create(), 'input'), $arguments);
			}

		}

		return $this->servant()->create()->action($id, $input)->run();
	}



	/**
	* Getters
	*/

	// 
	public function cache () {
		$arguments = func_get_args();
		return $this->getOrSet('cache', $arguments);
	}

	// 
	public function contentType () {
		$arguments = func_get_args();
		return $this->getOrSet('contentType', $arguments);
	}

	public function data () {
		return $this->getAndSet('data');
	}

	// Files in any format
	public function files ($format = false) {
		$files = $this->getAndSet('files');
		if ($format) {
			foreach ($files as $key => $filepath) {
				$files[$key] = $this->servant()->paths()->format($filepath, $format);
			}
		}
		return $files;
	}

	// 
	public function id () {
		return $this->getAndSet('id');
	}

	// 
	protected function input () {
		return $this->getAndSet('input');
	}

	// 
	public function isSite () {
		return $this->getAndSet('isSite');
	}

	// 
	public function output () {
		$arguments = func_get_args();
		return $this->getOrSet('output', $arguments);
	}

	// Path in any format
	protected function path ($format = false) {
		$path = $this->getAndSet('path');
		if ($format) {
			$path = $this->servant()->paths()->format($path, $format);
		}
		return $path;
	}

	// 
	public function status () {
		$arguments = func_get_args();
		return $this->getOrSet('status', $arguments);
	}



	/**
	* Setters
	*/

	/**
	* Disable or enable cache
	*/
	protected function setCache ($cache = true) {
		return $this->set('cache', $cache ? true : false);
	}

	/**
	* Content type
	*
	* A code for content type, available in settings. Should be available in settings.
	*/
	protected function setContentType ($input = null) {
		$contentType = '';

		// Valid content type passed
		if (is_string($input) or is_numeric($input)) {
			$contentType = trim(''.$input);
		}

		return $this->set('contentType', $contentType);
	}

	/**
	* Data service
	*/
	public function setData () {
		return $this->set('data', $this->servant()->create()->data($this->id()));
	}

	/**
	* Files
	*
	* List of all files of the action.
	*/
	protected function setFiles () {
		$files = array();
		$path = $this->path('server');

		// All files in directory
		if (is_dir($path)) {
			foreach (rglob_files($path, $this->servant()->constants()->formats('templates')) as $file) {
				$files[] = $this->servant()->paths()->format($file, false, 'server');
			}
		}

		return $this->set('files', $files);
	}

	/**
	* ID
	*
	* Name of the action (folder in the actions directory).
	*/
	protected function setId ($id = null) {
		if (!$this->servant()->available()->action($id)) {
			$this->fail($id.' is not available.');
		} else {
			return $this->set('id', $id);
		}
	}

	/**
	* Input
	*/
	protected function setInput ($input) {

		if ($this->getServantClass($input) !== 'input') {
			$this->fail('Invalid input passed to action.');

		// Input is acceptable
		} else {
			return $this->set('input', $input);
		}

	}

	/**
	* Whether or not this is the site action
	*/
	protected function setIsSite () {
		return $this->set('isSite', $this->id() === $this->servant()->constants()->actions('site'));
	}

	/**
	* Output
	*
	* The complete body content given for response.
	*
	* FLAG
	*   - Add support for images
	*/
	protected function setOutput ($input = null) {
		$output = '';

		// Valid output passed
		if (is_string($input) or is_numeric($input)) {
			$output = trim(''.$input);
		}

		return $this->set('output', $output);
	}

	/**
	* Path
	*
	* Action is a folder within the actions directory.
	*/
	protected function setPath () {
		return $this->set('path', $this->servant()->paths()->actions().$this->id().'/');
	}

	/**
	* Status
	*
	* Three-digit HTTP status code that indicates what happened in action. Should be available in settings.
	*/
	protected function setStatus ($status) {
		return $this->set('status', $status);
	}

}

?>