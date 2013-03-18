<?php

class ServantArticle extends ServantObject {

	// Properties
	protected $propertyId 		= null;
	protected $propertyIndex 	= null;
	protected $propertyLevel 	= null;
	protected $propertyName 	= null;
	protected $propertyParents 	= null;
	protected $propertySiblings = null;
	protected $propertySite 	= null;
	protected $propertyTree 	= null;
	protected $propertyType 	= null;
	protected $propertyOutput 	= null;
	protected $propertyPath 	= null;



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
	// public function id () {
	// 	return $this->getAndSet('id');
	// }
	// public function index () {
	// 	return $this->getAndSet('index');
	// }
	// public function level () {
	// 	return $this->getAndSet('level');
	// }
	// public function name () {
	// 	return $this->getAndSet('name');
	// }
	// public function output () {
	// 	return $this->getAndSet('output');
	// }
	// public function parents () {
	// 	$arguments = func_get_args();
	// 	return $this->getAndSet('parents', $arguments);
	// }
	// public function siblings () {
	// 	$arguments = func_get_args();
	// 	return $this->getAndSet('siblings', $arguments);
	// }
	// public function site () {
	// 	return $this->getAndSet('site');
	// }
	// public function tree () {
	// 	$arguments = func_get_args();
	// 	return $this->getAndSet('tree', $arguments);
	// }
	// public function type () {
	// 	return $this->getAndSet('type');
	// }
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
		$relativeUrl = implode('/', array_reverse($this->parents()));
		if (!empty($relativeUrl)) {
			$relativeUrl .= '/';
		}
		return $this->set('output', manipulateHtmlUrls($this->servant()->files()->read($this->path('server')), $this->site()->path('domain'), $relativeUrl));
	}

	protected function setParents () {
		$parents = array_reverse($this->tree());
		array_shift($parents);
		return $this->set('parents', $parents);
	}

	protected function setSiblings () {
		return $this->set('siblings', array_keys($this->site()->articles(array_reverse($this->parents()))));
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