<?php

class ServantResponse extends ServantObject {

	// Properties
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

	// Send a response
	public function send () {

		// Send headers
		foreach ($this->headers() as $value) {
			header($value);
		}

		// Output body content
		echo $this->body();

		return $this;
	}

	// Body content of the response
	public function body () {

		// Response has been saved
		if ($this->servant()->settings()->cache('server') > 0 and $this->exists()) {
			return file_get_contents($this->path('server'));

		// Response needs to be generated by an action
		} else {
			return $this->servant()->action()->output();
		}

	}

	// Save response into cache folder as file
	public function store () {
		$directory = pathinfo($this->path('server'), PATHINFO_DIRNAME);

		// Create directory if it doesn't exist
		if (!is_dir($directory)) {
			mkdir($directory, 0777, true);
		}

		// Save text or image resource
		if (is_resource($this->body()) and get_resource_type($this->body()) === 'gd') {
			return $this->storeImage($this->body(), $this->path('server'));
		} else {
			return $this->storeText($this->body(), $this->path('server'), $this->contentType());
		}

		return $this;
	}



	// Public getters

	// Paths can be fetched in any format
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

	// Max browser cache time in seconds
	// FLAG read from cache file if it's available
	protected function setBrowserCacheTime () {
		$time = 0;

		// Combination of global and action-specific settings
		$global = $this->servant()->settings()->cache('browser');
		$action = $this->servant()->action()->browserCache();

		// Only cache if both allow it
		if ($global and $action) {

			// Action has specified value
			if (is_int($action)) {
				$time = min($global, $action);

			// Action follows global setting
			} else {
				$time = $global;
			}
		}

		return $this->set('browserCacheTime', $time*60);
	}

	// Get content type from action
	// FLAG read from cache file if it's available
	protected function setContentType () {
		$contentType = $this->servant()->action()->contentType();

		// Invalid
		if (!$this->servant()->available()->contentType($contentType)) {
			$this->fail('No valid content type determined');

		// Valid
		} else {
			return $this->set('contentType', $contentType);
		}
	}

	// CORS is always on
	protected function setCors () {
		return $this->set('cors', true);
	}

	protected function setExists () {
		return $this->set('exists', is_file($this->path('server')) and filemtime($this->path('server')) < time()+($this->servant()->settings()->cache('server')*60));
	}

	// Relevant response items converted to HTTP header strings
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

	// Status comes from action
	// FLAG read from cache file if it's available
	protected function setStatus () {
		$status = $this->servant()->action()->status();

		// Invalid status
		if (!$this->servant()->available()->status($status)) {
			$this->fail('No valid status code determined');

		// Valid status
		} else {
			return $this->set('status', $status);
		}
	}

	protected function setPath () {
		$relativePath = implode('/', array_reverse($this->servant()->site()->article()->parents()));
		if (!empty($relativePath)) {
			$relativePath .= '/';
		}
		return $this->set('path', $this->servant()->paths()->cache().$this->servant()->action()->id().'/'.$this->servant()->site()->id().'/'.$relativePath.$this->servant()->article()->id().'.'.$this->status().'.'.$this->contentType());
	}



	/**
	* Private helpers
	*/

	// Save text content
	private function storeText ($content, $filepath) {
		file_put_contents($filepath, $content);
		return $this;
	}

	// Save image
	private function storeImage ($resource, $filepath, $contentType) {

		// JPG
		if ($contentType == 'jpg') {
			imagejpeg($resource, $filepath, 95);

		// PNG
		} else if ($contentType == 'png') {
			imagealphablending($resource, false);
			imagesavealpha($resource, true);
			imagepng($resource, $filepath, 0);

		// GIF
		} else if ($contentType == 'gif') {
			imagegif($resource, $filepath);
		}

		return $this;
	}


}

?>