<?php

class ServantMain extends ServantObject {

	// Children
	protected $propertyAvailable 	= null;
	protected $propertyFormat 		= null;
	protected $propertyInput 		= null;
	protected $propertyPaths 		= null;
	protected $propertyRender 		= null;
	protected $propertySettings 	= null;
	protected $propertySite 		= null;

	// Override default construction method
	public function __construct () {
		return $this;
	}

	// Custom launch method
	public function initialize ($paths, $settings, $input) {
		$this->setInput($input);
		$this->setPaths($paths);
		$this->setSettings($settings);
		return $this;
	}



	// Public getters for children
	public function input () {
		return $this->get('input');
	}
	public function paths () {
		return $this->get('paths');
	}
	public function settings () {
		return $this->get('settings');
	}

	public function available () {
		return $this->getAndSet('available');
	}
	public function format () {
		return $this->getAndSet('format');
	}
	public function render () {
		return $this->getAndSet('render');
	}
	public function site () {
		return $this->getAndSet('site');
	}



	// Setters for children
	protected function setInput ($input) {
		return $this->set('input', new ServantInput($this, $input));
	}
	protected function setPaths ($paths) {
		return $this->set('paths', new ServantPaths($this, $paths));
	}
	protected function setSettings ($settings) {
		return $this->set('settings', new ServantSettings($this, $settings));
	}

	protected function setAvailable () {
		return $this->set('available', new ServantAvailable($this));
	}
	protected function setFormat () {
		return $this->set('format', new ServantFormat($this));
	}
	protected function setRender () {
		return $this->set('render', new ServantRender($this));
	}
	protected function setSite () {
		return $this->set('site', new ServantSite($this));
	}



}

// // Current guide
// if (isset($_GET['category']) and array_key_exists($_GET['category'], $guides)) {
// 	$category = $_GET['category'];
	
// 	// Valid ID
// 	if (isset($_GET['id']) and array_key_exists($_GET['id'], $guides[$_GET['category']])) {
// 		$guide = $_GET['id'];
		
// 	// Category default
// 	} else {
// 		$temp = array_keys($guides[$category]);
// 		$guide = $temp[0];
// 	}

// // Fall back to readme
// } else {
// 	$category = $categoryorder[0];
// 	$temp = array_keys($guides[$category]);
// 	$guide = $temp[0];
// 	unset($temp);
// }
// $file = $guides[$category][$guide];

?>