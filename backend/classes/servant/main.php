<?php

class ServantMain extends ServantObject {

	// Override default construction method
	public function __construct () {
		return $this;
	}



	// Shorthand for full execution
	public function run ($paths, $settings, $site = null, $selectedArticle = null) {
		$this->initialize($paths, $settings)->select($site, $selectedArticle);
		if ($this->settings()->cache('server') > 0) {
			$this->cache();	
		}
		return $this->render();
	}



	// The Servant process

	// Paths and settings are needed
	public function initialize ($paths, $settings) {
		$this->setPaths($paths);
		$this->setSettings($settings);
		return $this;
	}

	// Select where to be
	public function select ($site, $selectedArticle) {
		$this->setSite($site, $selectedArticle);
		return $this;
	}

	// Store output into a file
	public function cache () {
		if (!$this->response()->exists()) {
			$this->response()->store();
		}
		return $this;
	}

	// Send output
	public function render () {

		// Send headers
		foreach ($this->response()->headers() as $value) {
			header($value);
		}

		// Output body content
		echo $this->response()->body();

		return $this;
	}



	// Children

	// Properties
	protected $propertyAvailable 	= null;
	protected $propertyExtract 		= null;
	protected $propertyFormat 		= null;
	protected $propertyHttpHeaders 	= null;
	protected $propertyPaths 		= null;
	protected $propertyResponse 	= null;
	protected $propertySettings 	= null;
	protected $propertySite 		= null;
	protected $propertyTemplate 	= null;
	protected $propertyTheme 		= null;

	// Public getters for children
	public function article () {
		return call_user_func_array(array($this->site(), 'article'), func_get_args());
	}
	public function available () {
		return $this->getAndSet('available');
	}
	public function extract () {
		return $this->getAndSet('extract');
	}
	public function format () {
		return $this->getAndSet('format');
	}
	public function httpHeaders () {
		return $this->getAndSet('httpHeaders');
	}
	public function paths () {
		return $this->getAndSet('paths');
	}
	public function response () {
		return $this->getAndSet('response');
	}
	public function settings () {
		return $this->getAndSet('settings');
	}
	public function site () {
		return $this->getAndSet('site');
	}
	public function template () {
		return $this->getAndSet('template');
	}
	public function theme () {
		return $this->getAndSet('theme');
	}



	// Setters for children
	protected function setAvailable () {
		return $this->set('available', new ServantAvailable($this));
	}
	protected function setExtract () {
		return $this->set('extract', new ServantExtract($this));
	}
	protected function setFormat () {
		return $this->set('format', new ServantFormat($this));
	}
	protected function setHttpHeaders () {
		return $this->set('httpHeaders', new ServantHttpHeaders($this));
	}
	protected function setPaths ($paths) {
		return $this->set('paths', new ServantPaths($this, $paths));
	}
	protected function setResponse () {
		return $this->set('response', new ServantResponse($this));
	}
	protected function setSettings ($settings = array()) {
		return $this->set('settings', new ServantSettings($this, $settings));
	}
	protected function setSite ($id = null, $selectedArticle = null) {
		return $this->set('site', new ServantSite($this, $id, $selectedArticle));
	}
	protected function setTemplate ($id = null) {
		return $this->set('template', new ServantTemplate($this, $id));
	}
	protected function setTheme ($id = null) {
		return $this->set('theme', new ServantTheme($this, $id));
	}



	// Built-in debugger
	protected $propertyDev = null;
	public function dev () {
		return $this->getAndSet('dev');
	}
	protected function setDev () {
		return $this->set('dev', new ServantDev($this));
	}

}

?>