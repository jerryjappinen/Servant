<?php

/**
* Article component
*
* The selected article in a ServantSite.
*
* Dependencies
*   - servant()->action()->id()
*   - servant()->files()->read()
*   - servant()->format()->name()
*   - servant()->format()->path()
*   - servant()->paths()->root()
*   - servant()->site()
*   - servant()->utilities()->load()
*/
class ServantArticle extends ServantObject {

	/**
	* Properties
	*/
	protected $propertyId 			= null;
	protected $propertyIndex 		= null;
	protected $propertyLevel 		= null;
	protected $propertyName 		= null;
	protected $propertyOutput 		= null;
	protected $propertyPath 		= null;
	protected $propertyParents 		= null;
	protected $propertyScripts 		= null;
	protected $propertySiblings 	= null;
	protected $propertySite 		= null;
	protected $propertyStylesheets 	= null;
	protected $propertyTree 		= null;
	protected $propertyType 		= null;



	/**
	* Select ID when initializing
	*/
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



	/**
	* Public getters
	*/

	public function path ($format = false) {
		$path = $this->getAndSet('path');
		if ($format) {
			$path = $this->servant()->format()->path($path, $format);
		}
		return $path;
	}

	public function scripts ($format = false) {
		$files = $this->getAndSet('scripts');
		if ($format) {
			foreach ($files as $key => $filepath) {
				$files[$key] = $this->servant()->format()->path($filepath, $format);
			}
		}
		return $files;
	}

	public function stylesheets ($format = false) {
		$files = $this->getAndSet('stylesheets');
		if ($format) {
			foreach ($files as $key => $filepath) {
				$files[$key] = $this->servant()->format()->path($filepath, $format);
			}
		}
		return $files;
	}



	/**
	* Setters
	*
	* NOTE
	*   - ->site() and ->tree() determine most of these
	*/

	protected function setId () {
		$tree = $this->tree();
		return $this->set('id', end($tree));
	}

	// Location of this article relative to its siblings
	protected function setIndex () {
		$siblings = array_flip($this->siblings());
		return $this->set('index', $siblings[$this->id()]);
	}

	// Depth of this article in site's article tree
	protected function setLevel () {
		return $this->set('level', count($this->tree()));
	}

	// Human-readable name, generated from ID
	protected function setName () {
		return $this->set('name', $this->servant()->format()->name($this->id(), $this->site()->settings('names')));
	}

	protected function setOutput () {

		// Root path for src attributes
		$srcUrl = $this->site()->path('domain');

		// Root path for hrefs
		$hrefUrl = $this->servant()->paths()->root('domain').$this->site()->id().'/'.$this->servant()->action()->id().'/';

		// Relative location for SRC urls
		$relativeSrcUrl = unprefix(dirname($this->path('plain')), $this->site()->path('plain'), true);
		if (!empty($relativeSrcUrl)) {
			$relativeSrcUrl .= '/';
		}

		// Relative location for HREF urls
		$relativeHrefUrl = suffix(implode('/', array_reverse($this->parents())), '/');

		return $this->set('output', manipulateHtmlUrls($this->servant()->files()->read($this->path('server')), $srcUrl, $relativeSrcUrl, $hrefUrl, $relativeHrefUrl));
	}

	// Parent nodes of this article in site's article tree, order is reversed
	protected function setParents () {
		$parents = array_reverse($this->tree());
		array_shift($parents);
		return $this->set('parents', $parents);
	}

	// Site scripts relevant to this article
	protected function setScripts () {
		return $this->set('scripts', $this->filterSiteFiles('scripts'));
	}

	// All articles on this level of the site article tree. Includes this article.
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

	// Site stylesheet relevant to this article
	protected function setStylesheets () {
		return $this->set('stylesheets', $this->filterSiteFiles('stylesheets'));
	}

	protected function setTree ($tree = null) {
		$tree = $this->selectArticle($this->site()->articles(), to_array($tree));
		return $this->set('tree', $tree);
	}

	protected function setType () {
		return $this->set('type', pathinfo($this->path(), PATHINFO_EXTENSION));
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

	// Select site's files that are relevant for this article
	private function filterSiteFiles ($type) {
		$results = array();

		// Allowed paths
		$allowed = array();
		foreach ($this->tree() as $key => $path) {
			if ($key > 0) {
				$allowed[] = $allowed[$key-1].$path.'/';
			} else {
				$allowed[] = $path.'/';
			}
		}
		// Also include root path
		array_unshift($allowed, '');
		unset($key, $path);

		// Traverse site's stylesheets, accept the ones on allowed levels
		foreach ($this->site()->$type() as $value) {
			$base = unprefix(dirname($value).'/', $this->site()->path(), true);
			if (in_array($base, $allowed)) {
				$results[] = $value;
			}
		}

		return $results;
	}

}

?>