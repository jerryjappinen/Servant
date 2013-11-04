<?php

class ServantMain extends ServantObject {

	// Override construction (no main object)
	public function __construct () {
		return $this;
	}



	/**
	* Initialization and execution
	*/
	public function initialize ($paths, $settings = null, $input = null, $debug = false) {

		// Set debug mode
		if ($debug) {
			$this->enableDebug();
		}

		// FLAG pages()->current() should be removed
		return $this->setPaths($paths)->setSettings($settings)->setInput($input)->setPages($this->input()->page());
	}

	public function run () {

		$this->purgeTemp();

		// Serve a response
		try {
			$response = $this->generate('response', $this->actions()->map($this->input()->action()));

		} catch (Exception $e) {
			$this->purgeTemp();

			// Serve an error page
			try {
				$actionId = $this->settings()->actions('error');
				$response = $this->generate('response', $this->actions()->map($actionId));

			// Fuck
			} catch (Exception $e) {
				$this->purgeTemp();
				$this->fail('Unknown error, cannot generate error page.');
			}

		}

		$this->purgeTemp();
		$this->serve($response);

		return $this;
	}

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
	* Debuggin mode
	*/
	protected $propertyDebug = false;
	public function debug () {
		return $this->get('debug');
	}
	protected function enableDebug () {
		return $this->set('debug', true);
	}



	/**
	* Public shortcuts
	*/

	public function page () {
		$arguments = func_get_args();
		return call_user_func_array(array($this->pages(), 'current'), $arguments);
	}



	/**
	* Private helpers
	*/

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
	protected $propertyFiles 		= null;
	protected $propertyFormat 		= null;
	protected $propertyHttpHeaders 	= null;
	protected $propertyInput 		= null;
	protected $propertyPages 		= null;
	protected $propertyParse 		= null;
	protected $propertyPaths 		= null;
	protected $propertySettings 	= null;
	protected $propertySite 		= null;
	protected $propertyTheme 		= null;
	protected $propertyUtilities 	= null;



	// Setters for children
	protected function setActions () {
		return $this->set('actions', create_object(new ServantActions($this))->init());
	}
	protected function setFiles () {
		return $this->set('files', create_object(new ServantFiles($this))->init());
	}
	protected function setFormat () {
		return $this->set('format', create_object(new ServantFormat($this))->init());
	}
	protected function setInput ($input) {
		return $this->set('input', create_object(new ServantInput($this))->init($input));
	}
	protected function setPages ($current) {
		return $this->set('pages', create_object(new ServantPages($this))->init($current));
	}
	protected function setParse () {
		return $this->set('parse', create_object(new ServantParse($this))->init());
	}
	protected function setPaths ($paths) {
		return $this->set('paths', create_object(new ServantPaths($this))->init($paths));
	}
	protected function setSettings ($settings = null) {
		return $this->set('settings', create_object(new ServantSettings($this))->init($settings));
	}
	protected function setSite () {
		return $this->set('site', create_object(new ServantSite($this))->init());
	}
	protected function setTheme () {
		return $this->set('theme', create_object(new ServantTheme($this))->init());
	}
	protected function setUtilities () {
		return $this->set('utilities', create_object(new ServantUtilities($this))->init());
	}

}

?>