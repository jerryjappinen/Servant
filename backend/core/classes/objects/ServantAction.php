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
	protected $propertyFiles 		= null;
	protected $propertyId 			= null;
	protected $propertyInput 		= null;
	protected $propertyIsRead 		= null;
	protected $propertyPath 		= null;
	protected $propertyOutput 		= null;
	protected $propertyStatus 		= null;



	/**
	* Data
	*
	* FLAG
	*   - This should be a ServantData object
	*/

	public function dataPath ($format = null) {
		return $this->servant()->paths()->dataOf($this->id(), $format);
	}



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
		$output = '';

		return $this->contentType($contentType)->status($status)->output($output);
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

		// Variables to pass to action's scripts
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
	*
	* FLAG nest methods should be removed, use ServantCreator
	*/
	public function nest ($id, $input = null) {
		if (!$input) {
			$input = $this->input();
		}
		return $this->servant()->create()->action($id, $input)->run();
	}

	/**
	* Generate a template
	*
	* FLAG nest methods should be removed, use ServantCreator
	*/
	public function nestTemplate ($id, $page, $content = null) {
		return $this->servant()->create()->template($id, $this, $page, $content)->output();
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

	// Files in any format
	protected function files ($format = false) {
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
	public function isRead () {
		return $this->getAndSet('isRead');
	}

	// 
	protected function input () {
		return $this->getAndSet('input');
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
	protected function setContentType ($contentType) {
		return $this->set('contentType', $contentType);
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
	protected function setIsRead () {
		return $this->set('isRead', $this->id() === $this->servant()->constants()->actions('read'));
	}

	/**
	* Output
	*
	* The complete body content given for response.
	*/
	protected function setOutput ($output) {
		return $this->set('output', trim(''.$output));
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