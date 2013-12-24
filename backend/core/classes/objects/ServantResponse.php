<?php

/**
* An HTTP response
*
* DEPENDENCIES
*   ???
*/
class ServantResponse extends ServantObject {

	/**
	* Properties
	*/
	protected $propertyAction 			= null;
	protected $propertyBody 			= null;
	protected $propertyBrowserCacheTime = null;
	protected $propertyContentType 		= null;
	protected $propertyCors 			= null;
	protected $propertyExisting 		= null;
	protected $propertyHeaders 			= null;
	protected $propertyInput 			= null;
	protected $propertyPath 			= null;
	protected $propertyStatus 			= null;



	/**
	* Take input upon initialization
	*/
	public function initialize () {

		// Set input
		$arguments = func_get_args();
		if (!empty($arguments)) {
			call_user_func_array(array($this, 'setInput'), $arguments);
		}

		// Generate response
		// FLAG this is a hack, body should just be auto set (now it's set here so that it fails ASAP because there's no proper error handling)
		$this->setBody();

		return $this;
	}



	/**
	* Getters
	*/

	public function action () {
		return $this->getAndSet('action');
	}

	public function body () {
		return $this->getAndSet('body');
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

	public function existing ($format = null) {
		$path = $this->getAndSet('existing');
		if ($format and !empty($path)) {
			$path = $this->servant()->paths()->format($path, $format);
		}
		return $path;
	}

	public function headers () {
		$arguments = func_get_args();
		return $this->getAndSet('headers', $arguments);
	}

	public function input () {
		return $this->getAndSet('input');
	}

	protected function path ($format = null) {
		$path = $this->getAndSet('path');
		if ($format) {
			$path = $this->servant()->paths()->format($path, $format);
		}
		return $path;
	}

	public function status () {
		return $this->getAndSet('status');
	}



	/**
	* Setters
	*/

	/**
	* Action used for this response
	*/
	protected function setAction () {

		// Select action based on input
		$pointer = $this->input()->pointer(0, true);
		$id = $this->selectAction($pointer);

		return $this->set('action', $this->servant()->create()->action($id, $this->input()));
	}



	/**
	* Generate response body
	*
	* NOTE
	*   - This is where we run the action if needed
	*/
	protected function setBody () {
		$cacheEnabled = ($this->servant()->site()->serverCache() > 0 and !$this->servant()->debug());
		$output = '';

		// Response has been saved
		if ($cacheEnabled and $this->existing()) {
			$output = file_get_contents($this->existing('server'));

		// Response needs to be generated
		} else {

			// Attempt to run action
			try {
				$this->action()->run();

				// Get output from action
				$output = $this->action()->output();

				// Store if needed
				if ($cacheEnabled and $this->action()->cache() and !$this->existing() and $this->status() < 400) {
					$this->store($output);
				}



			// Response fails
			} catch (Exception $exception) {
				$this->fail($this->action()->id().' action failed to run.'.($this->servant()->debug() ? ' ('.$exception->getMessage().')' : ''));
			}

		}

		return $this->set('body', $output);
	}



	/**
	* Max browser cache time in seconds
	*/
	protected function setBrowserCacheTime () {
		return $this->set('browserCacheTime', $this->servant()->debug() ? 0 : $this->servant()->site()->browserCache()*60);
	}



	/**
	* Get content type from action
	*/
	protected function setContentType () {
		$contentType = '';

		// Read content type from file extension
		if ($this->existing()) {
			$contentType = str_replace('.', '/', substr(pathinfo($this->existing(), PATHINFO_FILENAME), 4));

		// Get content type from action
		} else {
			$actionContentType = trim($this->action()->contentType());
			if (is_string($actionContentType)) {
				$contentType = $actionContentType;
			}
		}

		// System default
		if (!$contentType) {
			$contentType = $this->servant()->constants()->defaults('contentType');
		}

		// Real string defined in constants
		$constant = $this->servant()->constants()->contentTypes($contentType);
		if ($constant) {
			$contentType = $constant;
		}

		return $this->set('contentType', $contentType);
	}



	/**
	* CORS is always on as far as we're concerned
	*
	* FLAG
	*   - Is there a need for a setting?
	*/
	protected function setCors () {
		return $this->set('cors', true);
	}



	/**
	* Path to saved response, if one exists (otherwise empty string).
	*/
	protected function setExisting () {
		$result = '';

		// Look for a file that matches criteria work
		$path = $this->basePath('server');
		$potential = glob($path.'*.*.*.*');
		if (!empty($potential)) {
			$path = $potential[0];
		}

		// File exists and is not too old
		if (is_file($path) and filemtime($path) < time()+($this->servant()->site()->serverCache()*60)) {
			$result = $this->servant()->paths()->format($path, 'plain', 'server');
		}

		return $this->set('existing', $result);
	}



	/**
	* Relevant response items converted to HTTP header strings.
	*/
	protected function setHeaders () {

		// This is what's included
		$headers = array(
			$this->generateBrowserCacheTimeHeader($this->browserCacheTime()),
			$this->generateContentTypeHeader($this->contentType()),
			$this->generateCorsHeader($this->cors()),
			$this->generateStatusHeader($this->status()),
		);

		// Filter out empty headers
		$results = array();
		foreach ($headers as $value) {
			if (!empty($value)) {
				$results[] = $value;
			}
		}

		// Run internal methods for getting valid strings
		return $this->set('headers', $results);
	}



	/**
	* User input
	*/
	protected function setInput () {
		$arguments = func_get_args();
		$input = call_user_func_array(array($this->servant()->create(), 'input'), $arguments);
		return $this->set('input', $input);
	}



	/**
	* Where to look for or save the response content
	*/
	protected function setPath () {

		// Existing response
		if ($this->existing()) {
			$path = $this->existing();

		// Response doesn't exist, we'll be creating a new one
		} else {
			$contentType = $this->contentType();

			// Cache file extension
			$extension = 'cache';
			$constants = array_flip(array_reverse($this->servant()->constants()->contentTypes()));
			if (array_key_exists($contentType, $constants)) {
				$extension = $constants[$contentType];
			}

			// Full file path
			$path = $this->basePath().$this->status()
			   .'.'.str_replace('/', '.', $contentType)
			   .'.'.$extension;
		}

		return $this->set('path', $path);
	}



	/**
	* Status
	*/
	protected function setStatus () {

		// Read status from filename
		if ($this->existing()) {
			$split = explode('.', basename($this->existing()));
			$status = intval($split[0]);

		// Get status from action
		} else {
			$status = $this->action()->status();
		}



		// Invalid status
		if (!$this->servant()->constants()->statuses($status)) {
			$this->fail('Invalid status code given ('.$status.')');

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
	* Includes cache folder path, site ID, action ID and page tree. Used by path() and existing().
	*/
	private function basePath ($format = null) {

		// Base dir from settings
		$path = $this->servant()->paths()->cache($format).'/'.$this->action()->id().'/';

		// Each input permutation gets their own file
		// FLAG other input is not taken into account
		$path .= implode('/', $this->input()->pointer());

		return suffix($path, '/');
	}

	/**
	* Select action based on input
	*/
	private function selectAction ($input = null) {
		$result = null;

		if ($this->servant()->available()->action($input)) {
			$result = $input;

		// Silent fallback
		} else {

			// Global default (mapped through action names)
			$default = $this->servant()->constants()->defaults('action');
			$mapping = $this->servant()->constants()->actions($default);
			if ($mapping) {
				$default = $mapping;
			}
			unset($mapping);

			if ($this->servant()->available()->action($default)) {
				$result = $default;

			} else {

				// Whatever's available
				$actions = $this->servant()->available()->actions();
				if (!empty($actions)) {
					$result = $actions[0];

				// No actions, we fail
				} else {
					$this->fail('No actions available.');
				}

			}
		}



		return $result;
	}

	/**
	* Save text content
	*/
	private function store ($content) {
		$filepath = $this->path('server');
		$directory = dirname($filepath);

		// Create directory if it doesn't exist
		if (!is_dir($directory)) {

			// Catch permissions fail
			if (!@mkdir($directory, 0777, true)) {
				$this->warn('Can\'t create cache directory.');
			}

		}

		// Save file
		if (is_dir($directory) and is_writable($directory)) {
			file_put_contents($filepath, $content);
		}

		return $this;
	}



	/**
	* HTTP header helpers
	*/

	private function generateBrowserCacheTimeHeader ($time) {
		return 'Cache-Control: '.($time > 0 ? 'max-age='.$time : 'no-store');
	}

	private function generateContentTypeHeader ($contentType) {
		$headerString = 'Content-Type: '.$contentType;

		// Add character set if needed
		if (in_array(substr($contentType, 0, strpos($contentType, '/')), array('text', 'application'))) {
			$headerString .= '; charset=utf-8';
		}

		return $headerString;
	}

	private function generateCorsHeader ($enabled) {
		return ($enabled ? 'Access-Control-Allow-Origin: *' : '');
	}

	private function generateStatusHeader ($statusCode) {
		return 'HTTP/1.1 '.$this->servant()->constants()->statuses($statusCode);
	}

}

?>