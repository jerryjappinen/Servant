<?php

class ServantSite extends ServantObject {

	// Properties
	protected $propertyArticles = null;
	protected $propertyId 		= null;
	protected $propertyName 	= null;
	protected $propertyPath 	= null;
	protected $propertySelected = null;

	// Initialize with an ID
	protected function initialize ($id = '', $selected = array()) {
		$this->setId($id);
		$this->setSelected($selected);
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
		return $this->servant()->format()->path($this->servant()->paths()->sites().$this->id(), $format);
	}
	public function selected () {
		return $this->getAndSet('selected', func_get_args());
	}



	// Setters

	protected function setArticles () {
		$results = $this->findArticles($this->path('server'), $this->servant()->settings()->templateLanguages());
		ksort($results);
		return $this->set('articles', $results);
	}

	protected function setId ($id = '') {

		// Silently fall back to default site
		if (!$this->servant()->available()->site($id)) {
			$id = $this->servant()->available()->sites(0);
		}

		return $this->set('id', $id);
	}

	protected function setName () {
		return $this->set('name', $this->servant()->format()->name($this->id()));
	}

	protected function setPath () {
		return $this->set('path', $this->servant()->format()->path($this->servant()->paths()->sites().$this->id().'/', 'plain'));
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