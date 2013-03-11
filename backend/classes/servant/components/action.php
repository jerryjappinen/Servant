<?php

class ServantAction extends ServantObject {

	// Properties
	protected $propertyBrowserCache = null;
	protected $propertyContentType 	= null;
	protected $propertyId 			= null;
	protected $propertyOutput 		= null;
	protected $propertyStatus 		= null;



	// Defaults on
	public function initialize () {
		return $this->browserCache(true)->contentType('html')->status(200);
	}



	// Public getters

	// Get or set
	public function browserCache () {
		return $this->getOrSet('browserCache', func_get_args());
	}
	public function contentType () {
		return $this->getOrSet('contentType', func_get_args());
	}
	public function status () {
		return $this->getOrSet('status', func_get_args());
	}

	// Autosetters
	public function id () {
		return $this->getAndSet('id');
	}
	public function output () {
		return $this->getAndSet('output');
	}
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

	protected function setContentType ($contentType) {
		return $this->set('contentType', $contentType);
	}

	protected function setOutput () {
		return $this->set('output', $this->servant()->template()->extract());
	}

	protected function setPath () {
		return $this->set('path', $this->servant()->paths()->templates('plain').$this->id().'/');
	}

	protected function setStatus ($status) {
		return $this->set('status', $status);
	}

}

?>