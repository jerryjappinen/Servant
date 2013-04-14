<?php

/**
* Action component
*
* Running the current action.
*
* FLAG
*   - This component needs to be transformed into a separete action object that can be created whenever and wherever.
*
* Dependencies
*   - servant()->available()->action()
*   - servant()->available()->actions()
*   - servant()->files()->read()
*   - servant()->format()->path()
*   - servant()->input()->action()
*   - servant()->paths()->actions()
*   - servant()->settings()->defaults()
*/
class ServantAction extends ServantObject {

	/**
	* Properties
	*/
	protected $propertyContentType 			= null;
	protected $propertyFiles 				= null;
	protected $propertyId 					= null;
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
	* Get custom scripts from action's package, run them cleanly
	*/
	public function run () {

		// Include action's code
		try {
			foreach ($this->files('server') as $path) {
				$this->servant()->files()->read($path);
			}

		// If it fails, we create output like gentlemen
		// FLAG this isn't that great. We should switch to an error action now.
		} catch (Exception $exception) {
			$message = $exception->getCode() < 500 ? $exception->getMessage() : 'Something went wrong, and we\'re sorry. We\'ll try to fix it as soon as possible.';
			$this->contentType('html')->status($exception->getCode())->outputViaTemplate(true)->output('<p>'.$message.'</p>');
		}

		return $this;
	}

	/**
	* Initialize
	*
	* Defaults are set here, and can be overridden by action's code.
	*/
	public function initialize () {
		return $this->contentType('html')->status(200)->outputViaTemplate(false)->output('');
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

	// /**
	// * Browser cache
	// *
	// * Set max time in minutes, or allow/disallow caching of the response by browser.
	// */
	// protected function setBrowserCache ($time) {
	// 	$result = 0;

	// 	// True will allow caching if enabled in settings
	// 	if ($time === true) {
	// 		$result = true;

	// 	// Other times will be minutes
	// 	} else if (is_numeric($time) and $time > 0) {
	// 		$result = intval($time);
	// 	}

	// 	return $this->set('browserCache', $result);
	// }

	/**
	* Content type
	*
	* A code for content type, available in settings. Must be available in settings.
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

		// Individual file
		if (is_file($path)) {
			$files[] = $this->path('plain');

		// All files in directory
		} else if (is_dir($path)) {
			foreach (rglob_files($path, 'php') as $file) {
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
	protected function setId () {

		// Try using input
		$id = $this->servant()->input()->action();

		// Silent fallback
		if (!$this->servant()->available()->action($id)) {

			// Global default
			if ($this->servant()->available()->action($this->servant()->settings()->defaults('action'))) {
				$id = $this->servant()->settings()->defaults('action');

			// Whatever's available
			} else {
				$id = $this->servant()->available()->actions(0);
				if ($id === null) {
					$this->fail('No actions available');
				}
			}
		}

		return $this->set('id', $id);
	}

	/**
	* Output
	*
	* The complete body content given for response.
	*/
	protected function setOutput ($output) {
		return $this->set('output', ''.$output);
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
	* Action is either a file or a folder within the actions directory.
	*/
	protected function setPath () {
		$path = $this->servant()->paths()->actions('plain').$this->id();
		$serverPath = $this->servant()->paths()->actions('server').$this->id();

		// One PHP file
		if (is_file($serverPath.'.php')) {
			$path .= '.php';

		// Directory
		} else if (is_dir($serverPath.'/')) {
			$path .= '/';
		}

		return $this->set('path', $path);
	}

	/**
	* Status
	*
	* Three-digit HTTP status code that indicates what happened in action. Must be available in settings.
	*/
	protected function setStatus ($status) {
		return $this->set('status', $status);
	}

}

?>