<?php

class ServantAction extends ServantObject {

	// Properties
	protected $propertyBrowserCache = null;
	protected $propertyContent 		= null;
	protected $propertyContentType 	= null;
	protected $propertyId 			= null;
	protected $propertyOutput 		= null;
	protected $propertyStatus 		= null;



	// Defaults on
	public function initialize ($id = null) {
		if ($id) {
			$this->setId($id);
		}
		return $this->browserCache(true)->contentType('html')->status(200);
	}



	// Public getters

	// Get or set
	public function browserCache () {
		return $this->getOrSet('browserCache', func_get_args());
	}
	public function content () {
		return $this->getAndSet('content');
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

	// This is what action outputs, optionally via a template
	protected function setContent () {

		// Article title + content
		$content = '<h1 class="title-article">'.$this->servant()->article()->name().'</h1>
		'.$this->servant()->article()->output();

		return $this->set('content', $content);
	}

	protected function setContentType ($contentType) {
		return $this->set('contentType', $contentType);
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
	protected function setOutput () {
		return $this->set('output', $this->servant()->template()->output());
	}

	protected function setPath () {
		return $this->set('path', $this->servant()->paths()->actions('plain').$this->id().'/');
	}

	protected function setStatus ($status) {
		return $this->set('status', $status);
	}

}

?>