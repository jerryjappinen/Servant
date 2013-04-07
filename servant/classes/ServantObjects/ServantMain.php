<?php

class ServantMain extends ServantObject {

	// Override default construction method
	public function __construct () {
		return $this;
	}



	// Startup
	public function initialize ($paths, $settings, $input = null) {
		return $this->setPaths($paths)->setSettings($settings)->setInput($input);
	}

	// Full execution
	public function execute () {

		// Run action
		$this->action()->run();

		// Sometimes we store our response
		if ($this->settings()->cache('server') > 0 and !$this->response()->exists()) {
			$this->response()->store();
		}

		// And then we send a response
		$this->response()->send();
		return $this;
	}



	// Components

	// Properties
	protected $propertyAction 		= null;
	protected $propertyAvailable 	= null;
	protected $propertyFiles 		= null;
	protected $propertyFormat 		= null;
	protected $propertyHttpHeaders 	= null;
	protected $propertyInput 		= null;
	protected $propertyPaths 		= null;
	protected $propertyResponse 	= null;
	protected $propertySettings 	= null;
	protected $propertySite 		= null;
	protected $propertyTemplate 	= null;
	protected $propertyTheme 		= null;
	protected $propertyUtilities 	= null;

	// Public getters for children
	public function article () {
		$arguments = func_get_args();
		return call_user_func_array(array($this->site(), 'article'), $arguments);
	}



	// Setters for children
	protected function setAction ($id = null) {
		return $this->set('action', create(new ServantAction($this))->init($id));
	}
	protected function setAvailable () {
		return $this->set('available', create(new ServantAvailable($this))->init());
	}
	protected function setFiles () {
		return $this->set('files', create(new ServantFiles($this))->init());
	}
	protected function setFormat () {
		return $this->set('format', create(new ServantFormat($this))->init());
	}
	protected function setHttpHeaders () {
		return $this->set('httpHeaders', create(new ServantHttpHeaders($this))->init());
	}
	protected function setInput ($input) {
		return $this->set('input', create(new ServantInput($this))->init($input));
	}
	protected function setPaths ($paths) {
		return $this->set('paths', create(new ServantPaths($this))->init($paths));
	}
	protected function setResponse () {
		return $this->set('response', create(new ServantResponse($this))->init());
	}
	protected function setSettings ($settings = array()) {
		return $this->set('settings', create(new ServantSettings($this))->init($settings));
	}
	protected function setSite ($id = null, $selectedArticle = null) {
		return $this->set('site', create(new ServantSite($this))->init($id, $selectedArticle));
	}
	protected function setTemplate ($id = null) {
		return $this->set('template', create(new ServantTemplate($this))->init($id));
	}
	protected function setTheme ($id = null) {
		return $this->set('theme', create(new ServantTheme($this))->init($id));
	}
	protected function setUtilities () {
		return $this->set('utilities', create(new ServantUtilities($this))->init());
	}

}

?>