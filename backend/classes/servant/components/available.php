<?php

class ServantAvailable extends ServantObject {

	// Properties
	protected $propertyArticles 	= null;
	protected $propertySites 		= null;
	protected $propertyTemplates 	= null;
	protected $propertyThemes 		= null;

	// Public getters

	// Articles are dependent on current site
	public function article ($tree = null) {
		if ($tree and !is_array($tree)) {
			$tree = func_get_args();
		}
		return $this->assert('articles', $tree);
	}
	public function articles () {
		return call_user_func_array(array($this->servant()->site(), 'articles'), func_get_args());
	}

	// Others are pretty standard
	public function site ($id) {
		return in_array($id, $this->sites());
	}
	public function sites () {
		return $this->getAndSet('sites', func_get_args());
	}
	public function template ($id) {
		return in_array($id, $this->templates());
	}
	public function templates () {
		return $this->getAndSet('templates', func_get_args());
	}
	public function theme ($id) {
		return in_array($id, $this->themes());
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