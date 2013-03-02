<?php

class ServantAvailable extends ServantObject {

	// Properties
	protected $propertySites 		= null;
	protected $propertyTemplates 	= null;

	// Public getters
	public function site () {
		return $this->assert('sites', func_num_args());
	}
	public function sites () {
		return $this->getAndSet('sites', func_get_args());
	}
	public function template () {
		return $this->assert('templates', func_num_args());
	}
	public function templates () {
		return $this->getAndSet('templates', func_get_args());
	}



	// Setters

	// Sites are directories
	protected function setSites () {
		$sites = array();
		$dirs = glob_dir($this->servant()->paths()->sites('server'));
		foreach ($dirs as $value) {
			$sites[] = basename($value);
		}
		return $this->set('sites', $sites);
	}

	// Templates are directories
	protected function setTemplates () {
		$templates = array();
		$dirs = glob_dir($this->servant()->paths()->templates('server'));
		foreach ($dirs as $value) {
			$templates[] = basename($value);
		}
		return $this->set('templates', $templates);
	}

}

?>