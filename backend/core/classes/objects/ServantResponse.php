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
	protected $propertyRedirect 		= null;
	protected $propertyServerCacheTime 	= null;
	protected $propertyStatus 			= null;



	/**
	* Take input upon initialization
	*
	* FLAG
	*   - Should not run upon init
	*/
	protected function initialize () {

		// Set input
		$arguments = func_get_args();
		if (!empty($arguments)) {
			call_user_func_array(array($this, 'setInput'), $arguments);
		}

		// Run upon init
		return $this->run();
	}

	protected function run () {
		$redirectUrl = $this->findRedirectUrl(
			$this->input()->pointer(null, true),
			$this->servant()->manifest()->redirects()
		);

		// Redirect URL defined in manifest
		// FLAG requires URL to match exactly
		if ($redirectUrl) {
			$this->setRedirect($redirectUrl);

		// Generate response
		} else {

			// FLAG
			//   This is a hack, body should be auto-set.
			//   Now it's set here so that the action fails
			//   ASAP, since we dont' have proper error handling.
			$this->setBody();
			
		}

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

	public function redirect () {
		return $this->getAndSet('redirect');
	}

	public function serverCacheTime () {
		return $this->getAndSet('serverCacheTime');
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
		$cacheEnabled = ($this->serverCacheTime() > 0 and !$this->servant()->debug());
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
		$cacheTime = 0;

		// Find real cache time in production
		if (!$this->servant()->debug()) {		
			$default = $this->servant()->constants()->defaults('browserCache');
			$cacheTime = $this->resolveCacheTime($this->servant()->siteMap()->root()->browserCache(), $default);
		}

		return $this->set('browserCacheTime', $cacheTime * 60);
	}



	/**
	* Max server cache time in seconds
	*/
	protected function setServerCacheTime () {
		$cacheTime = 0;

		// Find real cache time in production
		if (!$this->servant()->debug()) {		
			$default = $this->servant()->constants()->defaults('serverCache');
			$cacheTime = $this->resolveCacheTime($this->servant()->siteMap()->root()->serverCache(), $default);
		}

		return $this->set('serverCacheTime', $cacheTime * 60);
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
		if (is_file($path) and filemtime($path) < time()+($this->serverCacheTime())) {
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
	* Redirect resource
	*/
	protected function setRedirect ($url = null) {
		$result = false;

		// Redirect to the set URL
		if ($url and is_string($url)) {
			$url = trim_text($url, true);
			if (!empty($url)) {

				// Format URL
				if (!$this->servant()->paths()->isAbsolute($url)) {
					$url = $this->servant()->paths()->format($url, 'url');
				}

				$result = $url;
			}
		}

		return $this->set('redirect', $result);
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
	* Base path of saved response. Used by path() and existing().
	*
	* Includes:
	*	- cache folder path
	*	- site ID
	*	- action ID
	*	- input pointer
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
	* Find a redirect url that matches the pointer given, if one exists
	*/
	private function findRedirectUrl ($pointer, $urls) {
		$result = null;

		// Pointer that can be used
		if (count($pointer)) {

			// Slash pointer parameters off one by one, starting from the end, see if a URL matches
			for ($i = count($pointer); $i > 0; $i--) { 
				$stringPointer = implode('/', array_slice($pointer, 0, $i));

				// URL found
				if (array_key_exists($stringPointer, $urls)) {
					$result = $urls[$stringPointer];
					break;
				}

				unset($stringPointer);
			}
		}

		return $result;
	}

	/**
	* Resolve valid cache time from multiple possibilities, in minutes
	*/
	private function resolveCacheTime ($input, $default) {
		$result = calculate($default);

		// No explicit value given
		if ($input !== null and $input !== true) {

			// Cache disabled
			if (!$input) {
				$input = 0;

			// Formula as a string
			} elseif (is_string($input)) {
				$input = calculate($input, true);
			}

			// Numerical value available
			if (is_numeric($input)) {
				$result = $input;
			}

		}

		return max(0, intval($result));
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