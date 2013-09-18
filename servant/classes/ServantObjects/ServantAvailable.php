<?php

class ServantAvailable extends ServantObject {

	/**
	* Properties
	*/
	protected $propertyActions 		= null;
	protected $propertyArticles 	= null;
	protected $propertyContentTypes = null;
	protected $propertyPatterns 	= null;
	protected $propertyStatuses 	= null;
	protected $propertyTemplates 	= null;
	protected $propertyThemes 		= null;
	protected $propertyUtilities 	= null;



	/**
	* Public getters
	*/

	/**
	* Articles are dependent on current site
	*/
	public function article () {
		$arguments = func_get_args();
		return $this->assert('articles', $arguments);
	}
	public function articles () {
		$arguments = func_get_args();
		return call_user_func_array(array($this->servant()->site(), 'articles'), $arguments);
	}

	/**
	* Others have extra assertion methods
	*/
	public function action ($id) {
		return in_array($id, $this->actions());
	}
	public function contentType ($id) {
		return in_array($id, $this->contentTypes());
	}
	public function pattern ($id) {
		return in_array($id, $this->patterns());
	}
	public function status ($id) {
		return in_array($id, $this->statuses());
	}
	public function template ($id) {
		return in_array($id, $this->templates());
	}
	public function theme ($id) {
		return in_array($id, $this->themes());
	}
	public function utility ($id) {
		return in_array($id, $this->utilities());
	}



	/**
	* Setters
	*/

	protected function setActions () {
		return $this->set('actions', array_merge($this->findFiles('actions', 'php'), $this->findDirectories('actions')));
	}

	protected function setContentTypes () {
		return $this->set('contentTypes', array_keys($this->servant()->settings()->contentTypes()));
	}

	protected function setPatterns () {
		return $this->set('patterns', array_keys($this->servant()->settings()->patterns()));
	}

	protected function setStatuses () {
		return $this->set('statuses', array_keys($this->servant()->settings()->statuses()));
	}

	/**
	* Templates
	*
	* Single template files or directories
	*/
	protected function setTemplates () {
		$formats = $this->servant()->settings()->formats('templates');
		return $this->set('templates', $this->findDirectories('templates', $formats));
	}

	/**
	* Themes
	*
	* Single files or directories of stylesheets and scripts
	*/
	protected function setThemes () {
		$formats = array_merge(
			array_flatten($this->servant()->settings()->formats('stylesheets')),
			array_flatten($this->servant()->settings()->formats('scripts'))
		);
		return $this->set('themes', $this->findDirectories('themes', $formats));
	}

	/**
	* Utilities
	*
	* Script files or directories
	*/
	protected function setUtilities () {
		return $this->set('utilities', array_merge($this->findFiles('utilities', 'php'), $this->findDirectories('utilities')));
	}



	/**
	* Private helpers
	*/

	private function findFiles ($dir, $types) {
		$items = array();
		$files = glob_files($this->servant()->paths()->$dir('server'), to_array($types));
		foreach ($files as $path) {
			$items[] = pathinfo($path, PATHINFO_FILENAME);
		}
		return $items;
	}

	/**
	* Find directories with at least one supported file
	*
	* FLAG
	*   - This is pretty slow. We basically go through all files in all template and theme directories.
	*/
	private function findDirectories ($dir, $formats = array()) {
		$items = array();
		$dirs = glob_dir($this->servant()->paths()->$dir('server'));
		foreach ($dirs as $path) {
			$files = rglob_files($path, $formats);
			if (!empty($files)) {
				$items[] = basename($path);
			}
			unset($files);
		}
		return $items;
	}

}

?>