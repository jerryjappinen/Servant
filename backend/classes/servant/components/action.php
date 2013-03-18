<?php

class ServantAction extends ServantObject {

	// Properties
	protected $propertyBrowserCache = null;
	protected $propertyContent 		= null;
	protected $propertyContentType 	= null;
	protected $propertyFiles 		= null;
	protected $propertyId 			= null;
	protected $propertyPath 		= null;
	protected $propertyOutput 		= null;
	protected $propertyStatus 		= null;



	// Initialization
	public function initialize ($id = null) {
		if ($id) {
			$this->setId($id);
		}

		// Set defaults
		return $this->browserCache(true)->content('')->contentType('html')->status(200);
	}

	// Run custom scripts in action
	public function run () {

		// Include action's code
		foreach ($this->files('server') as $path) {
			$this->servant()->files()->run($path);
		}

		return $this;
	}



	// Public getters/setters

	// Whether or not to allow browsers to cache response
	public function browserCache () {
		$arguments = func_get_args();
		return $this->getOrSet('browserCache', $arguments);
	}

	// Content can be embedded into templates
	public function content () {
		$arguments = func_get_args();
		return $this->getOrSet('content', $arguments);
	}

	// Content type is a short extension (from settings) that marks the type of output
	public function contentType () {
		$arguments = func_get_args();
		return $this->getOrSet('contentType', $arguments);
	}

	// Output is the complete body content given for response
	public function output () {

		// Output defaults to content
		if ($this->get('output') === null and func_num_args() === 0) {
			return $this->content();

		// ...but can also live its own life
		} else {
		$arguments = func_get_args();
			return $this->getOrSet('output', $arguments);
		}

	}

	// Status is a numerical HTTP status code (from settings) that indicates what happened in action 
	public function status () {
		$arguments = func_get_args();
		return $this->getOrSet('status', $arguments);
	}

	// Getters with autosetters

	// File list in any format
	public function files ($format = false) {
		$files = $this->getAndSet('files');
		if ($format) {
			foreach ($files as $key => $filepath) {
				$files[$key] = $this->servant()->format()->path($filepath, $format);
			}
		}
		return $files;
	}

	// public function id () {
	// 	return $this->getAndSet('id');
	// }

	// Allow formatting path when getting
	public function path ($format = false) {
		$path = $this->getAndSet('path');
		if ($format) {
			$path = $this->servant()->format()->path($path, $format);
		}
		return $path;
	}



	// Setters

	// Set max time in minutes
	protected function setBrowserCache ($time) {
		$result = 0;

		// True will allow caching if enabled in settings
		if ($time === true) {
			$result = true;

		// Other times will be minutes
		} else if (is_numeric($time) and $time > 0) {
			$result = intval($time);
		}

		return $this->set('browserCache', $result);
	}

	// This is what action outputs, optionally via a template
	protected function setContent ($content) {
		return $this->set('content', $content);
	}

	protected function setContentType ($contentType) {
		return $this->set('contentType', $contentType);
	}

	// All files within action
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

	protected function setId ($id = null) {

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

	// Output structure via template
	protected function setOutput ($output) {
		return $this->set('output', $output);
	}

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

	protected function setStatus ($status) {
		return $this->set('status', $status);
	}

}

?>