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



	// Public getters

	public function body () {

		// Cached response
		if ($this->servant()->settings()->cache('server') > 0 and $this->exists()) {
			return file_get_contents($this->path('server'));

		// Generate via template
		} else {
			return $this->servant()->template()->extract();
		}

	}

	public function browserCacheTime () {
		return $this->getAndSet('browserCacheTime');
	}

	public function contentType () {
		return $this->getAndSet('contentType');
	}

	public function cors () {
		return $this->getAndSet('cors');
	}

	public function exists () {
		return $this->getAndSet('exists');
	}

	public function headers () {
		return $this->getAndSet('headers', func_get_args());
	}

	public function path ($format = false) {
		$path = $this->getAndSet('path');
		if ($format) {
			$path = $this->servant()->format()->path($path, $format);
		}
		return $path;
	}

	public function status () {
		return $this->getAndSet('status');
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



	// Setters

	public function setBrowserCacheTime () {
		$setting = $this->servant()->settings()->cache('browser');
		return $this->set('browserCacheTime', 'Cache-Control: '.($setting > 0 ? 'max-age='.($setting*60) : 'no-store'));
	}

	public function setContentType () {
		return $this->set('contentType', 'Content-Type: text/html; charset=utf-8');
	}

	public function setCors () {
		return $this->set('cors', 'Access-Control-Allow-Origin: *');
	}

	public function setExists () {
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

		// Run internal methods for getting the headers
		foreach ($headers as $key => $value) {
			$headers[$key] = $this->$key();
		}

		return $this->set('headers', $headers);
	}

	public function setStatus () {
		return $this->set('status', 'HTTP/1.1 200 OK');
	}

	public function setPath () {
		$relativePath = implode('/', array_reverse($this->servant()->site()->article()->parents()));
		if (!empty($relativePath)) {
			$relativePath .= '/';
		}
		return $this->set('path', $this->servant()->paths()->cache().$this->servant()->site()->id().'/'.$relativePath.$this->servant()->article()->id().'.html');
	}



	// Private helpers

	// Save text content
	private function storeText ($content, $filepath) {
		file_put_contents($filepath, $content);
		return $this;
	}

	// Save image
	private function storeImage ($resource, $filepath, $contentType) {

		// JPG
		if ($contentType == 'image/jpeg') {
			imagejpeg($resource, $filepath, 95);

		// PNG
		} else if ($contentType == 'image/png') {
			imagealphablending($resource, false);
			imagesavealpha($resource, true);
			imagepng($resource, $filepath, 0);

		// GIF
		} else if ($contentType == 'image/gif') {
			imagegif($resource, $filepath);
		}

		return $this;
	}


}

?>