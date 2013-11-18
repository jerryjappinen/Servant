<?php

/**
* An action
*
* FLAG
*   - Action should take input, not a page in initialization (they can create a page if needed or select it from the sitemap)
*
* DEPENDENCIES
*   ServantFiles 	-> read
*   ServantFormat 	-> path
*   ServantPaths 	-> actions
*   ServantSettings -> defaults
*/
class ServantAction extends ServantObject {

	/**
	* Properties
	*/
	protected $propertyContentType 			= null;
	protected $propertyFiles 				= null;
	protected $propertyId 					= null;
	protected $propertyIsRead 				= null;
	protected $propertyPage 				= null;
	protected $propertyPath 				= null;
	protected $propertyOutput 				= null;
	protected $propertyStatus 				= null;



	/**
	* Initialize
	*
	* Defaults are set here, and can be overridden by action's code.
	*/
	public function initialize ($id, $page) {

		// Set ID and action upon initialization
		$this->setId($id);
		$this->setPage($page);

		// Defaults
		$contentType = $this->servant()->settings()->defaults('contentType');
		$status = $this->servant()->settings()->defaults('status');
		$output = '';

		return $this->contentType($contentType)->status($status)->output($output);
	}



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

		// Variables to pass to action's scripts
		$scriptVariables = array(
			'servant' => $this->servant(),
			'page' => $this->page(),
			'action' => $this,
		);

		// FLAG we should run any template files like this
		// foreach ($this->files('server') as $path) {
		// 	$this->servant()->files()->read($path, $scriptVariables);
		// }

		run_scripts($this->files('server'), $scriptVariables);

		return $this;
	}

	/**
	* Generate a child action
	*/
	public function nest ($id) {
		return $this->servant()->create()->action($id, $this->page())->run();
	}

	/**
	* Generate a template
	*/
	public function nestTemplate ($id, $content = null) {
		return $this->servant()->create()->template($id, $this, $this->page(), $content)->output();
	}



	/**
	* Public getters
	*/

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

	public function page () {
		return $this->get('page');
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

			// FLAG until we can run any script sets cleanly, not just PHP with Baseline's run_scripts
			$formats = 'php';

			// $formats = array_flatten($this->servant()->settings()->formats('templates'));
			foreach (rglob_files($path, $formats) as $file) {
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
	* Path
	*
	* Action is a folder within the actions directory.
	*/
	protected function setPath () {
		return $this->set('path', $this->servant()->paths()->actions().$this->id().'/');
	}

	/**
	* Current page
	*/
	protected function setPage ($page) {
	
		if ($this->getServantClass($page) !== 'page') {
			$this->fail('Invalid page passed to template.');

		// Page is acceptable
		} else {
			return $this->set('page', $page);
		}

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