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
		$arguments = func_get_args();
		return $this->assert('articles', $arguments);
	}
	public function articles () {
		$arguments = func_get_args();
		return call_user_func_array(array($this->servant()->site(), 'articles'), $arguments);
	}

	// Others are pretty standard
	public function action ($id) {
		return in_array($id, $this->actions());
	}
	// public function actions () {
	// 	$arguments = func_get_args();
	// 	return $this->getAndSet('actions', $arguments);
	// }
	public function contentType ($id) {
		return in_array($id, $this->contentTypes());
	}
	// public function contentTypes () {
	// 	$arguments = func_get_args();
	// 	return $this->getAndSet('contentTypes', $arguments);
	// }
	public function pattern ($id) {
		return in_array($id, $this->patterns());
	}
	// public function patterns () {
	// 	$arguments = func_get_args();
	// 	return $this->getAndSet('patterns', $arguments);
	// }
	public function site ($id) {
		return in_array($id, $this->sites());
	}
	// public function sites () {
	// 	$arguments = func_get_args();
	// 	return $this->getAndSet('sites', $arguments);
	// }
	public function status ($id) {
		return in_array($id, $this->statuses());
	}
	// public function statuses () {
	// 	$arguments = func_get_args();
	// 	return $this->getAndSet('statuses', $arguments);
	// }
	public function template ($id) {
		return in_array($id, $this->templates());
	}
	// public function templates () {
	// 	$arguments = func_get_args();
	// 	return $this->getAndSet('templates', $arguments);
	// }
	public function theme ($id) {
		return in_array($id, $this->themes());
	}
	// public function themes () {
	// 	$arguments = func_get_args();
	// 	return $this->getAndSet('themes', $arguments);
	// }



	// Setters

	// Sites, templates and themes are all just files and/or directories
	protected function setActions () {
		return $this->set('actions', array_merge($this->findFiles('actions', 'php'), $this->findDirectories('actions')));
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

	private function findFiles ($dir, $types) {
		$items = array();
		$files = glob_files($this->servant()->paths()->$dir('server'), to_array($types));
		foreach ($files as $path) {
			$items[] = pathinfo($path, PATHINFO_FILENAME);
		}
		return $items;
	}

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