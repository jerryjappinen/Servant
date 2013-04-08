<?php

class ServantResponse extends ServantObject {

	/**
	* Properties
	*/
	protected $propertyBody 			= null;
	protected $propertyBrowserCacheTime = null;
	protected $propertyContentType 		= null;
	protected $propertyCors 			= null;
	protected $propertyExists 			= null;
	protected $propertyHeaders 			= null;
	protected $propertyPath 			= null;
	protected $propertyStatus 			= null;
	protected $propertyStore 			= null;



	/**
	* Wrapper methods
	*/

	/**
	* Send a response
	*
	* FLAG
	* - we shouldn't run action if cache's response exists
	* - most of this should be done in ServantMain()
	* - need to sort out when action is run... maybe its execution should be all under init(), and action isn't even created until we know response doesn't already exist
	* - it's shitty when I have to check if response exists everywhere, but I need to just assume action isn't run then
	* - exists() and path() need to be less strict about the input type, and accept the first one that's close enough
	*/
	public function serve () {
		$cacheEnabled = $this->servant()->settings()->cache('server') > 0;	

		// Response has been saved
		if ($cacheEnabled and $this->exists()) {
			$output = file_get_contents($this->existingPath('server'));

		// Response needs to be generated
		} else {

			// Run action
			$this->servant()->action()->run();

			// Get action's output, possibly via template
			$output = $this->servant()->action()->outputViaTemplate() ? $this->servant()->template()->output() : $this->servant()->action()->output();

			// Store if needed
			if ($this->contentType() < 400 and $cacheEnabled and !$this->exists()) {
				$this->store($output);
			}

		}

		// Send headers & print body
		foreach ($this->headers() as $value) {
			header($value);
		}
		echo $output;

		return $this;
	}



	/**
	* Special getters
	*/

	public function exists () {
		return $this->getAndSet('exists');
	}

	/**
	* Path in any format
	*/
	public function path ($format = false) {
		$path = $this->getAndSet('path');
		if ($format) {
			$path = $this->servant()->format()->path($path, $format);
		}
		return $path;
	}



	/**
	* Setters
	*/

	/**
	* Max browser cache time in seconds comes from global settings.
	*/
	protected function setBrowserCacheTime () {
		return $this->set('browserCacheTime', $this->servant()->settings()->cache('browser')*60);
	}



	/**
	* Get content type from action
	*/
	protected function setContentType () {

		// Read content type from file extension
		if ($this->exists()) {
			$contentType = pathinfo($this->existingPath(), PATHINFO_EXTENSION);

		// Get content type from action
		} else {
			$contentType = $this->servant()->action()->contentType();
		}

		// Invalid
		if (!$this->servant()->available()->contentType($contentType)) {
			$this->fail('No valid content type determined');

		// Valid
		} else {
			return $this->set('contentType', $contentType);
		}
	}



	/**
	* CORS is always on for now.
	*/
	protected function setCors () {
		return $this->set('cors', true);
	}



	/**
	* Whether or not response has already been saved.
	*
	* NOTE
	* - Action isn't run when a response already exists
	*/
	protected function setExists () {

		// Look for a file that matches criteria work
		$path = $this->basePath('server');
		$potential = glob($path.'.*.*');
		if (!empty($potential)) {
			$path = $potential[0];
		}
		debug($path);

		return $this->set('exists', is_file($path) and filemtime($path) < time()+($this->servant()->settings()->cache('server')*60));
	}



	/**
	* Relevant response items converted to HTTP header strings.
	*/
	protected function setHeaders () {

		// This is what's included
		$headers = array(
			'browserCacheTime' => '',
			'contentType' => '',
			'cors' => '',
			'status' => '',
		);

		// Run internal methods for getting valid strings
		foreach ($headers as $key => $value) {
			$headers[$key] = $this->servant()->httpHeaders()->$key();
		}

		return $this->set('headers', $headers);
	}



	/**
	* Where to look for or save the response content
	*/
	protected function setPath () {

		// Base

		// Existing response
		// FLAG duplicating logic from exists()
		if ($this->exists()) {
			$path = $this->basePath('server');
			$potential = glob($path.'.*.*');
			$path = $potential[0];

		// Response doesn't exist, we'll be creating a new one
		// FLAG this is dangerous, action must have been run
		} else {
			$path = $this->basePath().'.'.$this->status().'.'.$this->contentType();
		}

		return $this->set('path', $path);
	}



	/**
	* Status
	*/
	protected function setStatus () {

		// Read status from filename
		if ($this->exists()) {
			$status = explode('.', basename($this->existingPath()));
			$status = $status[count($status)-2];

		// Get status from action
		} else {
			$status = $this->servant()->action()->status();
		}


		// Invalid status
		if (!$this->servant()->available()->status($status)) {
			$this->fail('No valid status code given');

		// Valid status
		} else {
			return $this->set('status', $status);
		}
	}



	/**
	* Private helpers
	*/

	/**
	* Base path of saved response.
	*
	* Includes cache folder path, site ID, action ID and article tree. Used by path() and exists().
	*/
	private function basePath ($format = null) {
		return $this->servant()->paths()->cache($format).$this->servant()->site()->id().'/'.$this->servant()->action()->id().'/'.implode('/', $this->servant()->site()->article()->tree());
	}

	/**
	* Save text content
	*/
	private function store ($content) {
		$filepath = $this->path('server');
		$directory = pathinfo($filepath, PATHINFO_DIRNAME);

		// Create directory if it doesn't exist
		if (!is_dir($directory)) {
			mkdir($directory, 0777, true);
		}

		file_put_contents($filepath, $content);

		return $this;
	}

}

?>