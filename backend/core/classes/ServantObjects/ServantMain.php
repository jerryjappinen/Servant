<?php

class ServantMain extends ServantObject {

	// Override construction (no main object)
	public function __construct () {
		return $this;
	}



	/**
	* Initialization and execution flow
	*/

	// General initializations
	public function initialize ($paths, $settings = null, $debug = false) {

		// Set debug mode
		if ($debug) {
			$this->enableDebug();
		}

		return $this->setPaths($paths)->setSettings($settings);
	}

	// Run with actions
	public function run ($userInput = null) {

		$this->purgeTemp();

		$input = $this->generate('input', $userInput);

		// FLAG pages()->current() should be removed
		$this->setPages($input->page());

		// Serve a response
		try {
			$response = $this->generate('response', $this->generate('action', $input->action()));

		} catch (Exception $e) {
			$this->purgeTemp();

			// Serve an error page
			try {
				$actionId = $this->settings()->actions('error');
				$response = $this->generate('response', $this->generate('action', $actionId));

			// Fuck
			} catch (Exception $e) {
				$this->purgeTemp();
				$this->fail($this->debug() ? $e->getMessage() : 'Unknown error, cannot generate error page.');
			}

		}

		$this->purgeTemp();
		$this->serve($response);

		return $this;
	}

	// Serve a response
	public function serve ($response) {

		// Send headers
		foreach ($response->headers() as $value) {
			header($value);
		}

		// Print body (assuming string)
		echo $response->body();

		return $this;
	}



	/**
	* Public shortcuts
	*/
	public function debug () {
		return $this->get('debug');
	}



	/**
	* Debugging mode
	*/
	protected $propertyDebug = false;
	protected function enableDebug () {
		return $this->set('debug', true);
	}

	/**
	* Purge and remove the temp directory
	*/
	private function purgeTemp () {
		remove_dir($this->paths()->temp('server'));
		return $this;
	}



	/**
	* Services
	*/

	protected $propertyActions 		= null;
	protected $propertyPages 		= null;
	protected $propertyPaths 		= null;
	protected $propertySettings 	= null;
	protected $propertySite 		= null;
	protected $propertyTheme 		= null;
	protected $propertyUtilities 	= null;

	protected function setActions () {
		return $this->set('actions', $this->generate('actions'));
	}
	protected function setPages ($current) {
		return $this->set('pages', $this->generate('pages', $current));
	}
	protected function setPaths ($paths) {
		return $this->set('paths', $this->generate('paths', $paths));
	}
	protected function setSettings ($settings = null) {
		return $this->set('settings', $this->generate('settings', $settings));
	}
	protected function setSite () {
		return $this->set('site', $this->generate('site'));
	}
	protected function setTheme () {
		return $this->set('theme', $this->generate('theme'));
	}
	protected function setUtilities () {
		return $this->set('utilities', $this->generate('utilities'));
	}



	/**
	* Utility-like services
	*/

	protected $propertyFiles 		= null;
	protected $propertyFormat 		= null;
	protected $propertyParse 		= null;

	protected function setFiles () {
		return $this->set('files', $this->generate('files'));
	}
	protected function setFormat () {
		return $this->set('format', $this->generate('format'));
	}
	protected function setParse () {
		return $this->set('parse', $this->generate('parse'));
	}



}

?>