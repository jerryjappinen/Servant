<?php

class ServantMain extends ServantObject {

	/**
	* Override constructor (would normally require main)
	*/
	public function __construct () {
		return $this;
	}

	/**
	* Debug mode
	*/
	protected $propertyDebug = null;
	protected function setDebug () {
		return $this->set('debug', false);
	}
	protected function enableDebug () {
		return $this->set('debug', true);
	}



	/**
	* Services
	*/

	protected $propertyAvailable 	= null;
	protected $propertyCreate 		= null;
	protected $propertyFiles 		= null;
	protected $propertyParse 		= null;
	protected $propertyPaths 		= null;
	protected $propertySettings 	= null;
	protected $propertySite 		= null;
	protected $propertyUtilities 	= null;

	protected function setAvailable () {
		return $this->set('available', $this->generate('available'));
	}
	protected function setCreate () {
		return $this->set('create', $this->generate('creator'));
	}
	protected function setFiles () {
		return $this->set('files', $this->generate('files'));
	}
	protected function setParse () {
		return $this->set('parse', $this->generate('parser'));
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
	protected function setUtilities () {
		return $this->set('utilities', $this->generate('utilities'));
	}

	/**
	* Deprecated
	*/
	protected $propertyPages = null;
	protected function setPages () {
		return $this->set('pages', $this->generate('pages'));
	}



	/**
	* Flow
	*/

	/**
	* Wake-up
	*/
	public function initialize ($paths, $settings = null, $debug = false) {
		if ($debug) {
			$this->enableDebug();
		}
		return $this->setPaths($paths)->setSettings($settings);
	}

	/**
	* Run actions, generate response
	*/
	public function response ($userInput = null) {
		$this->purgeTemp();

		// User input
		$input = $this->generate('input', $userInput);

		// Serve a response
		try {
			$response = $this->generate('response', $input->action(), $input->page());

		} catch (Exception $e) {
			$this->purgeTemp();

			if ($this->debug()) {
				echo html_dump($e->getMessage());
			}

			// Serve an error page
			try {
				$response = $this->generate('response', $this->settings()->actions('error'), $input->page());

			// Fuck
			} catch (Exception $e) {
				$this->purgeTemp();
				$this->fail($this->debug() ? $e->getMessage() : 'Unknown error, cannot generate error page.');
			}

		}

		$this->purgeTemp();

		return $response;
	}

	/**
	* Serve the response that was created based on input
	*/
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
	* Private helpers
	*/

	/**
	* Purge and remove the temp directory
	*/
	private function purgeTemp () {
		remove_dir($this->paths()->temp('server'));
		return $this;
	}

}

?>