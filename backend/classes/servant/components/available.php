<?php

class ServantAvailable extends ServantObject {

	// Properties
	protected $propertyActions 		= null;
	protected $propertyArticles 	= null;
	protected $propertyContentTypes = null;
	protected $propertyPatterns 	= null;
	protected $propertySites 		= null;
	protected $propertyStatuses 	= null;
	protected $propertyTemplates 	= null;
	protected $propertyThemes 		= null;



	// Public getters

	// Articles are dependent on current site
	public function article () {
		return $this->assert('articles', func_get_args());
	}
	public function articles () {
		return call_user_func_array(array($this->servant()->site(), 'articles'), func_get_args());
	}

	// Others are pretty standard
	public function action ($id) {
		return in_array($id, $this->actions());
	}
	public function actions () {
		return $this->getAndSet('actions', func_get_args());
	}
	public function contentType ($id) {
		return in_array($id, $this->contentTypes());
	}
	public function contentTypes () {
		return $this->getAndSet('contentTypes', func_get_args());
	}
	public function pattern ($id) {
		return in_array($id, $this->patterns());
	}
	public function patterns () {
		return $this->getAndSet('patterns', func_get_args());
	}
	public function site ($id) {
		return in_array($id, $this->sites());
	}
	public function sites () {
		return $this->getAndSet('sites', func_get_args());
	}
	public function status ($id) {
		return in_array($id, $this->statuses());
	}
	public function statuses () {
		return $this->getAndSet('statuses', func_get_args());
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
	protected function setActions () {
		return $this->set('actions', $this->findDirectories('actions'));
	}
	protected function setContentTypes () {
		return $this->set('contentTypes', array_keys($this->servant()->settings()->contentTypes()));
	}
	protected function setPatterns () {
		return $this->set('patterns', array_keys($this->servant()->settings()->patterns()));
	}
	protected function setSites () {
		return $this->set('sites', $this->findDirectories('sites'));
	}
	protected function setStatuses () {
		return $this->set('statuses', array_keys($this->servant()->settings()->statuses()));
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