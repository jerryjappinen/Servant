<?php

class ServantMain extends ServantObject {

	// Override construction (no main object)
	public function __construct () {
		return $this;
	}



	/**
	* Initialization
	*/
	public function initialize ($paths, $settings = null, $input = null, $debug = false) {

		// Set debug mode
		if ($debug) {
			$this->enableDebug();
		}

		// FLAG clear temp directory at this point

		return $this->setPaths($paths)->setSettings($settings)->setInput($input);
	}



	/**
	* Execute Servant to generate a response
	*/
	public function run () {

		// FLAG last-resort thing, not sure how to handle this
		try {

			// Serve a response
			$this->response($this->actions()->current())->serve();

		} catch (Exception $e) {

			// Fuck
			echo '<p style="margin: 0 auto; padding: 5%; font-family: sans-serif; text-align: center; font-size: 1.4em; color: #333;">What went to shit:<br /><br /><strong style="">'.$e->getMessage().'</strong>.</p>';

		}

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

	public function action () {
		$arguments = func_get_args();
		return call_user_func_array(array($this->actions(), 'current'), $arguments);
	}

	public function page () {
		$arguments = func_get_args();
		return call_user_func_array(array($this->pages(), 'current'), $arguments);
	}



	/**
	* Convenience getters
	*
	* FLAG
	*   - there should be a method for creating these in ServantObject
	*   - is this really the place for this?
	*/

	// Create and initialize a new template
	// NOTE this is public
	public function template () {
		$arguments = func_get_args();
		$template = create_object(new ServantTemplate($this));
		call_user_func_array(array($template, 'init'), $arguments);
		return $template->output();
	}

	// Create and initialize a new response
	private function response () {
		$arguments = func_get_args();
		$response = create_object(new ServantResponse($this));
		call_user_func_array(array($response, 'init'), $arguments);
		return $response;
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
	protected function setPages () {
		return $this->set('pages', create_object(new ServantPages($this))->init());
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