<?php

class ServantSite extends ServantObject {

	// Properties
	protected $propertyArticle 	= null;
	protected $propertyArticles = null;
	protected $propertyId 		= null;
	protected $propertyName 	= null;
	protected $propertyPath 	= null;



	// Select ID and article while initializing
	protected function initialize ($id = null, $selectedArticle = null) {
		if ($id) {
			$this->setId($id);
		}
		if ($selectedArticle) {
			$this->setArticle($selectedArticle);
		}
		return $this;
	}



	// Public getters
	public function article () {
		return $this->getAndSet('article');
	}
	public function articles () {
		return $this->getAndSet('articles', func_get_args());
	}
	public function id () {
		return $this->getAndSet('id');
	}
	public function name () {
		return $this->getAndSet('name');
	}
	public function path ($format = null) {
		$path = $this->getAndSet('path');
		if ($format) {
			$path = $this->servant()->format()->path($path, $format);
		}
		return $path;
	}



	// Setters

	protected function setArticle ($selectedArticle = null) {
		return $this->set('article', new ServantArticle($this->servant(), $this, $selectedArticle));
	}

	protected function setArticles () {
		$results = $this->findFiles($this->path('server'), $this->servant()->settings()->formats('templates'));
		return $this->set('articles', $results);
	}

	protected function setId ($id = null) {

		// Given ID is invalid
		if (!$id or !$this->servant()->available()->site($id)) {

			// Silently fall back to default
			$default = $this->servant()->settings()->defaults('site');
			$first = $this->servant()->available()->sites(0);
			if ($this->servant()->available()->site($default)) {
				$id = $default;

			// ... or the first site available
			} else if (isset($first)) {
				$id = $first;

			// No sites
			} else {
				$this->fail('No sites available');
			}

		}

		return $this->set('id', $id);
	}

	protected function setName () {
		return $this->set('name', $this->servant()->format()->name($this->id()));
	}

	protected function setPath () {
		return $this->set('path', $this->servant()->paths()->sites('plain').$this->id().'/');
	}



	// Private helpers

	// List available articles recursively
	private function findFiles ($path, $filetypes = array()) {
		$results = array();

		// Files on this level
		foreach (glob_files($path, $filetypes) as $file) {
			$results[pathinfo($file, PATHINFO_FILENAME)] = $this->servant()->format()->path($file, 'plain', 'server');
		}

		// Non-empty child directories
		foreach (glob_dir($path) as $subdir) {
			$value = $this->findFiles($subdir);
			if (!empty($value)) {
				$results[pathinfo($subdir, PATHINFO_FILENAME)] = $this->findFiles($subdir);
			}
		}

		// Sort alphabetically
		ksort($results);

		return $results;
	}

}

?>