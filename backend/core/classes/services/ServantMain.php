<?php

class ServantMain extends ServantObject {



	/**
	* Override constructor (would normally require main)
	*/
	public function __construct () {
		return $this;
	}



	/**
	* Properties
	*/

	protected $propertyAssets 		= null;
	protected $propertyAvailable 	= null;
	protected $propertyConstants 	= null;
	protected $propertyCreate 		= null;
	protected $propertyDebug 		= null;
	protected $propertyFiles 		= null;
	protected $propertyManifest 	= null;
	protected $propertyPaths 		= null;
	protected $propertySitemap 		= null;
	protected $propertyUtilities 	= null;
	protected $propertyWarnings 	= null;



	/**
	* Getters
	*/

	public function assets () {
		return $this->getAndSet('assets');
	}
	public function available () {
		return $this->getAndSet('available');
	}
	public function constants () {
		return $this->getAndSet('constants');
	}
	public function create () {
		return $this->getAndSet('create');
	}
	public function debug () {
		return $this->getAndSet('debug');
	}
	public function files () {
		return $this->getAndSet('files');
	}
	public function manifest () {
		return $this->getAndSet('manifest');
	}
	public function paths () {
		return $this->getAndSet('paths');
	}
	public function sitemap () {
		return $this->getAndSet('sitemap');
	}
	public function utilities () {
		return $this->getAndSet('utilities');
	}
	public function warnings () {
		return $this->getAndSet('warnings');
	}



	/**
	* Setters
	*/

	protected function setAssets ($path = null) {
		return $this->set('assets', $this->generate('assets', $path));
	}
	protected function setAvailable () {
		return $this->set('available', $this->generate('available'));
	}
	protected function setConstants ($constants = null) {
		return $this->set('constants', $this->generate('constants', $constants));
	}
	protected function setCreate () {
		return $this->set('create', $this->generate('creator'));
	}
	protected function setDebug () {
		return $this->set('debug', false);
	}
	protected function setFiles () {
		return $this->set('files', $this->generate('files'));
	}
	protected function setManifest ($path = null) {
		return $this->set('manifest', $this->generate('manifest', $path));
	}
	protected function setPaths ($paths) {
		return $this->set('paths', $this->generate('paths', $paths));
	}
	protected function setSitemap ($path = null) {
		return $this->set('sitemap', $this->generate('sitemap', $path));
	}
	protected function setUtilities () {
		return $this->set('utilities', $this->generate('utilities'));
	}
	protected function setWarnings () {
		return $this->set('warnings', $this->generate('warnings'));
	}



	/**
	* Birth
	*/
	public function initialize ($paths, $constants = null, $debug = false) {
		if ($debug) {
			$this->enableDebug();
		}
		return $this->setPaths($paths)->setConstants($constants);
	}



	/**
	* Get ready (access manifest and assets)
	*/
	public function setup () {
		return $this
			->setManifest($this->paths()->manifest())
			->setSitemap($this->paths()->pages())
			->setAssets($this->paths()->assets())
		;
	}



	/**
	* Go
	*/

	/**
	* Run actions, generate response
	*/
	public function response ($get = array(), $post = array(), $put = array(), $delete = array(), $files = array()) {
		$this->purgeTemp();

		// Serve a response
		try {
			$response = $this->create()->response($get, $post, $put, $delete, $files);

		} catch (Exception $e) {
			$this->purgeTemp();

			if ($this->debug()) {
				echo log_dump('FAIL', $e->getMessage());
			}

			// Serve an error page (fake input)
			try {
				$response = $this->create()->response(array('action' => $this->constants()->actions('error')));

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

	// Enable debug mode, which is off by default
	protected function enableDebug () {
		return $this->set('debug', true);
	}

	// Purge and remove the temp directory
	private function purgeTemp () {
		remove_dir($this->paths()->temp('server'));
		return $this;
	}

}

?>