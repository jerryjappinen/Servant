<?php

class ServantSite extends ServantObject {

	// Properties
	protected $propertyArticles = null;
	protected $propertyId 		= null;
	protected $propertyName 	= null;
	protected $propertyPath 	= null;
	protected $propertySelected = null;



	// Select ID and article while initializing
	protected function initialize ($id = false, $selected = false) {
		if ($id) {
			$this->setId($id);
		}
		if ($selected) {
			$this->setSelected($selected);
		}
		return $this;
	}



	// Public getters
	public function articles () {
		return $this->getAndSet('articles', func_get_args());
	}
	public function id () {
		return $this->getAndSet('id');
	}
	public function name () {
		return $this->getAndSet('name');
	}
	public function path ($format = false) {
		$path = $this->getAndSet('path');
		if ($format) {
			$path = $this->servant()->format()->path($path, $format);
		}
		return $path;
	}
	public function selected () {
		return $this->getAndSet('selected', func_get_args());
	}



	// Setters

	protected function setArticles () {
		$results = $this->findArticles($this->path('server'), $this->servant()->settings()->templateFiles());
		ksort($results);
		return $this->set('articles', $results);
	}

	protected function setId ($id = '') {

		// Silently fall back to default
		if (!$this->servant()->available()->site($id)) {
			$id = $this->servant()->available()->sites(0);
		}

		return $this->set('id', $id);
	}

	protected function setName () {
		return $this->set('name', $this->servant()->format()->name($this->id()));
	}

	protected function setPath () {
		return $this->set('path', $this->servant()->paths()->sites('plain').$this->id().'/');
	}

	protected function setSelected () {
		return $this->set('selected', array());
	}



	// Private helpers

	// List available articles recursively
	private function findArticles ($path, $filetypes = array()) {
		$results = array();
		foreach (glob_files($path, $filetypes) as $file) {
			$results[pathinfo($file, PATHINFO_FILENAME)] = basename($file);
		}
		foreach (glob_dir($path) as $subdir) {
			$results[pathinfo($subdir, PATHINFO_FILENAME)] = $this->findArticles($subdir);
		}
		return $results;
	}

}

?>