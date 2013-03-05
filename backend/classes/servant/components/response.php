<?php

class ServantResponse extends ServantObject {

	// Properties
	protected $propertyBody 				= null;
	protected $propertyBrowserCacheTime 	= null;
	protected $propertyContentType 			= null;
	protected $propertyCors 				= null;
	protected $propertyHeaders 				= null;
	protected $propertyStatus 				= null;



	// Public getters

	public function browserCacheTime () {
		return $this->getAndSet('browserCacheTime');
	}

	public function body () {
		return $this->servant()->template()->extract();
	}

	public function contentType () {
		return $this->getAndSet('contentType');
	}

	public function cors () {
		return $this->getAndSet('cors');
	}

	public function headers () {
		return $this->getAndSet('headers', func_get_args());
	}

	public function status () {
		return $this->getAndSet('status');
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

}

?>