<?php

// protected $propertyLevel 		= null;
// protected $propertyCategory 		= null;
// protected $propertySiblings 		= null;
class ServantArticle extends ServantObject {

	// Properties
	protected $propertyId 				= null;
	protected $propertyName 			= null;
	protected $propertySite 			= null;
	protected $propertyTree 			= null;
	protected $propertyType 			= null;
	protected $propertyPath 			= null;



	// Select ID when initializing
	protected function initialize ($site = null, $tree = null) {
		if ($site) {
			$this->setSite($site);
		}
		if ($tree) {
			$this->setTree($tree);
		}
		return $this;
	}



	// Public getters
	public function id () {
		return $this->getAndSet('id');
	}
	public function name () {
		return $this->getAndSet('name');
	}
	public function site () {
		return $this->getAndSet('site');
	}
	public function tree () {
		return $this->getAndSet('tree', func_get_args());
	}
	public function type () {
		return $this->getAndSet('type');
	}
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

	protected function setName () {
		return $this->set('name', $this->servant()->format()->name($this->id()));
	}

	protected function setSite ($site = null) {

		// Fall back to primary site
		if (!$site or get_class($site) !== 'ServantSite') {
			$site = $this->servant()->site();
		}

		return $this->set('site', $site);
	}

	protected function setTree ($tree = null) {
		return $this->set('tree', $this->selectArticle($this->site()->articles(), to_array($tree)));
	}

	protected function setType () {
		return $this->set('type', detect($this->path(), 'extension'));
	}

	protected function setPath () {
		return $this->set('path', $this->site()->articles($this->tree()));
	}



	// Private helpers

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
			return $tree;
		}

	}

}

?>