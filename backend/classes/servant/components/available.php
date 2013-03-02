<?php

class ServantAvailable extends ServantObject {

	// Properties
	protected $propertySites 		= null;
	protected $propertyTemplates 	= null;
	protected $propertyThemes 		= null;

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
	public function theme () {
		return $this->assert('themes', func_num_args());
	}
	public function themes () {
		return $this->getAndSet('themes', func_get_args());
	}



	// Setters

	// Sites, templates and themes are all just directories
	protected function setSites () {
		return $this->set('sites', $this->findDirectories('sites'));
	}
	protected function setTemplates () {
		return $this->set('templates', $this->findDirectories('templates'));
	}
	protected function setThemes () {
		return $this->set('themes', $this->findDirectories('themes'));
	}



	// Private helpers

	private function findDirectories ($dir) {
		$items = array();
		$dirs = glob_dir($this->servant()->paths()->$dir('server'));
		foreach ($dirs as $path) {
			$items[] = basename($path);
		}
		return $items;
	}

}

?>