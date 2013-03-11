<?php

class ServantAction extends ServantObject {

	// Properties
	protected $propertyBrowserCache = null;
	protected $propertyContentType 	= null;
	protected $propertyOutput 		= null;
	protected $propertyStatus 		= null;



	// Defaults on
	public function initialize () {
		return $this->browserCache(true)->contentType('html')->status(200);
	}



	// Public getters

	public function browserCache () {
		return $this->getOrSet('browserCache', func_get_args());
	}

	public function contentType () {
		return $this->getOrSet('contentType', func_get_args());
	}

	public function status () {
		return $this->getOrSet('status', func_get_args());
	}

	public function output () {
		return $this->getAndSet('output');
	}



	// Setters

	// Set max time in minutes; or set to true to allow caching by global settings; "false" accepted to mean 0
	public function setBrowserCache ($time) {
		$result = 0;
		if ($time === true) {
			$result = true;
		} else if (is_numeric($time) and $time > 0) {
			$result = intval($time);
		}
		return $this->set('browserCache', $result);
	}

	public function setContentType ($contentType) {
		return $this->set('contentType', $contentType);
	}

	public function setStatus ($status) {
		return $this->set('status', $status);
	}

	public function setOutput () {
		return $this->set('output', $this->servant()->template()->extract());
	}

}

?>