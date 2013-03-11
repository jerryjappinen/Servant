<?php

class ServantMain extends ServantObject {

	// Override default construction method
	public function __construct () {
		return $this;
	}



	// Full execution
	public function execute ($paths, $settings, $action = null, $site = null, $selectedArticle = null) {

		// We initialize and select things
		$this->setPaths($paths)->setSettings($settings)->setAction($action)->setSite($site, $selectedArticle);

		// Sometimes we store our response
		if ($this->settings()->cache('server') > 0 and !$this->response()->exists()) {
			$this->response()->store();
		}

		// And then we send a response
		$this->response()->send();
		return $this;
	}



	// Children

	// Properties
	protected $propertyAction 		= null;
	protected $propertyAvailable 	= null;
	protected $propertyRun 			= null;
	protected $propertyFormat 		= null;
	protected $propertyHttpHeaders 	= null;
	protected $propertyPaths 		= null;
	protected $propertyResponse 	= null;
	protected $propertySettings 	= null;
	protected $propertySite 		= null;
	protected $propertyTemplate 	= null;
	protected $propertyTheme 		= null;

	// Public getters for children
	public function action () {
		return $this->getAndSet('action');
	}
	public function article () {
		return call_user_func_array(array($this->site(), 'article'), func_get_args());
	}
	public function available () {
		return $this->getAndSet('available');
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
	public function run () {
		return $this->getAndSet('run');
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
	protected function setAction ($id = null) {
		return $this->set('action', new ServantAction($this, $id));
	}
	protected function setAvailable () {
		return $this->set('available', new ServantAvailable($this));
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
	protected function setRun () {
		return $this->set('run', new ServantRun($this));
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