<?php

class ServantHttpHeaders extends ServantObject {

	// Properties
	protected $propertyBrowserCacheTime = null;
	protected $propertyContentType 		= null;
	protected $propertyCors 			= null;
	protected $propertyStatus 			= null;



	// Public getters
	// public function browserCacheTime () {
	// 	return $this->getAndSet('browserCacheTime');
	// }
	// public function contentType () {
	// 	return $this->getAndSet('contentType');
	// }
	// public function cors () {
	// 	return $this->getAndSet('cors');
	// }
	// public function status () {
	// 	return $this->getAndSet('status');
	// }



	// Setters

	protected function setBrowserCacheTime () {
		$time = $this->servant()->response()->browserCacheTime();
		return $this->set('browserCacheTime', 'Cache-Control: '.($time > 0 ? 'max-age='.$time : 'no-store'));
	}

	protected function setContentType () {
		$contentType = $this->servant()->settings()->contentTypes($this->servant()->response()->contentType());
		$headerString = 'Content-Type: '.$contentType;

		// Add character set if needed
		if (in_array(substr($contentType, 0, strpos($contentType, '/')), array('text', 'application'))) {
			$headerString .= '; charset=utf-8';
		}

		return $this->set('contentType', $headerString);
	}

	protected function setCors () {
		return $this->set('cors', ($this->servant()->response()->cors() ? 'Access-Control-Allow-Origin: *' : ''));
	}

	protected function setStatus () {
		return $this->set('status', 'HTTP/1.1 '.$this->servant()->settings()->statuses($this->servant()->response()->status()));
	}

}

?>