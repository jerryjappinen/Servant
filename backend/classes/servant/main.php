<?php

class ServantMain extends ServantObject {

	// Shorthand for full execution
	public function run ($paths, $settings, $site = false, $selectedArticle = false) {
		return $this->initialize($paths, $settings)->select($site, $selectedArticle)->render()->debug();
	}



	// The Servant process

	// Override default construction method
	public function __construct () {
		return $this;
	}

	// Paths and settings are needed
	public function initialize ($paths, $settings) {
		$this->setPaths($paths);
		$this->setSettings($settings);
		return $this;
	}

	// Select where to be
	public function select ($site, $selection) {
		$this->setSite($site, $selection);
		return $this;
	}

	// Create output via templates
	public function render () {
		foreach ($this->template()->files('server') as $path) {
			echo $this->extract()->file($path);
		}
		return $this;
	}



	// Children

	// Properties
	protected $propertyAvailable 	= null;
	protected $propertyExtract 		= null;
	protected $propertyFormat 		= null;
	protected $propertyPaths 		= null;
	protected $propertySettings 	= null;
	protected $propertySite 		= null;
	protected $propertyTemplate 	= null;
	protected $propertyTheme 		= null;

	// Public getters for children
	public function available () {
		return $this->getAndSet('available');
	}
	public function extract () {
		return $this->getAndSet('extract');
	}
	public function format () {
		return $this->getAndSet('format');
	}
	public function paths () {
		return $this->get('paths');
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
	protected function setPaths ($paths) {
		return $this->set('paths', new ServantPaths($this, $paths));
	}
	protected function setSettings ($settings = array()) {
		return $this->set('settings', new ServantSettings($this, $settings));
	}
	protected function setSite ($id = null, $selection = null) {
		return $this->set('site', new ServantSite($this, $id, $selection));
	}
	protected function setTemplate ($id = null) {
		return $this->set('template', new ServantTemplate($this, $id));
	}
	protected function setTheme ($id = null) {
		return $this->set('theme', new ServantTheme($this, $id));
	}



	// Debug info
	public function debug () {
		$whatToPrint = array(
			// 'available' => $this->dev()->available(),
			// 'paths' => $this->dev()->paths(),
			'theme' => $this->theme()->files(),
		);
		echo htmlDump($whatToPrint);
		return $this;
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