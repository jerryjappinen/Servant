<?php

/**
* An action
*
* FLAG
*   - This component needs to be transformed into a separete action object that can be created whenever and wherever.
*
* Dependencies
*   - servant -> files -> read
*   - servant -> format -> path
*   - servant -> paths -> actions
*   - servant -> settings -> defaults
*/
class ServantAction extends ServantObject {

	/**
	* Properties
	*/
	protected $propertyContentType 			= null;
	protected $propertyFiles 				= null;
	protected $propertyId 					= null;
	protected $propertyIsRead 				= null;
	protected $propertyPath 				= null;
	protected $propertyOutput 				= null;
	protected $propertyOutputViaTemplate 	= null;
	protected $propertyStatus 				= null;



	/**
	* Wrapper methods
	*/

	/**
	* Run
	*
	* Run custom scripts from action's package cleanly
	*
	* FLAG
	*   - I should create a dummy object for action's scripts so that $this and variable scope works nicely
	*/
	public function run () {
		foreach ($this->files('server') as $path) {
			$this->servant()->files()->read($path, array(
				'servant' => $this->servant(),
				'page' => $this->servant()->pages()->current(),
				'action' => $this,
			));
		}
		return $this;
	}

	/**
	* Initialize
	*
	* Defaults are set here, and can be overridden by action's code.
	*/
	public function initialize ($id) {

		// Set ID upon initialization
		$this->setId($id);

		// Defaults
		$contentType = $this->servant()->settings()->defaults('contentType');
		$status = $this->servant()->settings()->defaults('status');
		$outputViaTemplate = false;
		$output = '';

		return $this->contentType($contentType)->status($status)->outputViaTemplate($outputViaTemplate)->output($output);
	}



	/**
	* Public getters
	*/

	public function contentType () {
		$arguments = func_get_args();
		return $this->getOrSet('contentType', $arguments);
	}

	// Files in any format
	public function files ($format = false) {
		$files = $this->getAndSet('files');
		if ($format) {
			foreach ($files as $key => $filepath) {
				$files[$key] = $this->servant()->format()->path($filepath, $format);
			}
		}
		return $files;
	}

	public function output () {
		$arguments = func_get_args();
		return $this->getOrSet('output', $arguments);
	}

	public function outputViaTemplate () {
		$arguments = func_get_args();
		return $this->getOrSet('outputViaTemplate', $arguments);
	}

	// Path in any format
	public function path ($format = false) {
		$path = $this->getAndSet('path');
		if ($format) {
			$path = $this->servant()->format()->path($path, $format);
		}
		return $path;
	}

	public function status () {
		$arguments = func_get_args();
		return $this->getOrSet('status', $arguments);
	}



	/**
	* Setters
	*/

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
			foreach (rglob_files($path, array_flatten($this->servant()->settings()->formats('templates'))) as $file) {
				$files[] = $this->servant()->format()->path($file, false, 'server');
			}
		}

		return $this->set('files', $files);
	}

	/**
	* ID
	*
	* Name of the action (file or folder in the actions directory).
	*/
	protected function setId ($id = null) {
		return $this->set('id', $id);
	}

	/**
	* Whether or not this is the site action
	*/
	protected function setIsRead () {
		return $this->set('isRead', $this->id() === $this->servant()->settings()->actions('read'));
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
	* Output via template
	*
	* Choose to use template or go without when printing output.
	*/
	protected function setOutputViaTemplate ($value) {
		return $this->set('outputViaTemplate', ($value ? true : false));
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