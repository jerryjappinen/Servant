<?php

class ServantArticle extends ServantObject {

	// Properties
	protected $propertyId 		= null;
	protected $propertyIndex 	= null;
	protected $propertyLevel 	= null;
	protected $propertyName 	= null;
	protected $propertyOutput 	= null;
	protected $propertyPath 	= null;
	protected $propertyParents 	= null;
	protected $propertySiblings = null;
	protected $propertySite 	= null;
	protected $propertyTree 	= null;
	protected $propertyType 	= null;



	// Select ID when initializing
	public function initialize ($site = null, $tree = null) {

		// Load utilities
		$this->servant()->utilities()->load('urls');

		// Select things
		if ($site) {
			$this->setSite($site);
		}
		if ($tree) {
			$this->setTree($tree);
		}

		return $this;
	}



	// Public getters
	public function path ($format = false) {
		$path = $this->getAndSet('path');
		if ($format) {
			$path = $this->servant()->format()->path($path, $format);
		}
		return $path;
	}



	// Setters
	// NOTE site and tree determine most of these

	protected function setId () {
		$tree = $this->tree();
		return $this->set('id', end($tree));
	}

	protected function setIndex () {
		$siblings = array_flip($this->siblings());
		return $this->set('index', $siblings[$this->id()]);
	}

	protected function setLevel () {
		return $this->set('level', count($this->tree()));
	}

	protected function setName () {
		return $this->set('name', $this->servant()->format()->name($this->id()));
	}

	protected function setOutput () {

		// Root path for src attributes
		$srcUrl = $this->site()->path('domain');

		// Root path for hrefs
		$hrefUrl = $this->servant()->paths()->root('domain').$this->site()->id().'/'.$this->servant()->action()->id().'/';

		// Relative location for URLs
		$relativeUrl = substr(pathinfo($this->path('plain'), PATHINFO_DIRNAME), strlen($this->site()->path('plain')));
		if (!empty($relativeUrl)) {
			$relativeUrl .= '/';
		}

		return $this->set('output', manipulateHtmlUrls($this->servant()->files()->read($this->path('server')), $srcUrl, $relativeUrl, $hrefUrl, $relativeUrl));
	}

	protected function setParents () {
		$parents = array_reverse($this->tree());
		array_shift($parents);
		return $this->set('parents', $parents);
	}

	protected function setSiblings () {
		$siblings = array_keys($this->site()->articles(array_reverse($this->parents())));
		return $this->set('siblings', empty($siblings) ? array() : $siblings);
	}

	protected function setSite ($site = null) {

		// Fall back to primary site
		if (!$site or get_class($site) !== 'ServantSite') {
			$site = $this->servant()->site();
		}

		return $this->set('site', $site);
	}

	protected function setTree ($tree = null) {
		$tree = $this->selectArticle($this->site()->articles(), to_array($tree));
		return $this->set('tree', $tree);
	}

	protected function setType () {
		return $this->set('type', detect($this->path(), 'extension'));
	}

	protected function setPath () {
		return $this->set('path', $this->site()->articles($this->tree()));
	}



	// Private helpers

	// Choose one article from those available, preferring the one detailed in $tree
	private function selectArticle ($articlesOnThisLevel, $tree, $level = 0) {
 
		// No preference or preferred item doesn't exist: auto select
		if (!isset($tree[$level]) or !array_key_exists($tree[$level], $articlesOnThisLevel)) {

			// Cut out the rest of the preferred items
			$tree = array_slice($tree, 0, $level);

			// Auto select first item on this level
			$keys = array_keys($articlesOnThisLevel);
			$tree[] = $keys[0];

		}

		// We need to go deeper
		if (is_array($articlesOnThisLevel[$tree[$level]])) {
			return $this->selectArticle($articlesOnThisLevel[$tree[$level]], $tree, $level+1);

		// That was it
		} else {
			return array_slice($tree, 0, $level+1);
		}

	}

}

?>