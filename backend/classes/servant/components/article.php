<?php

// FLAG only works for primary site, not any site
	// protected $propertySite 			= null;
class ServantArticle extends ServantObject {

	// Properties
	protected $propertyId 				= null;
	protected $propertyName 			= null;
	protected $propertyTree 			= null;
	protected $propertyType 			= null;
	protected $propertyPath 			= null;



	// Select ID when initializing
	protected function initialize ($tree = null) {
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
	public function tree () {
		return $this->getAndSet('name', func_get_args());
	}
	public function type () {
		return $this->getAndSet('name');
	}
	public function path ($format = false) {
		$path = $this->getAndSet('path');
		if ($format) {
			$path = $this->servant()->format()->path($path, $format);
		}
		return $path;
	}



	// Setters

	protected function setId () {
		$keys = array_keys($this->tree());
		return $this->set('id', $keys[count($this->tree)-1]);
	}

	protected function setName () {
		return $this->set('name', $this->servant()->format()->name($this->id()));
	}

	// Others are generated from tree
	// FLAG site class has this stuff
	protected function setTree ($tree = null) {
		if (!$this->servant()->available()->article($tree)) {
			$tree = array();
		}
		return $this->set('tree', $tree);
	}

	protected function setType () {
		return $this->set('path', detect($this->path(), 'extension'));
	}

	protected function setPath () {
		return $this->set('path', $this->servant()->site()->path('plain').implode('/', $this->tree()).'/');
	}

}

?>