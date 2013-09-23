<?php

/**
* Page component
*
* The selected page in a ServantSite.
*
* Dependencies
*   - servant()->action()->id()
*   - servant()->files()->read()
*   - servant()->format()->pageName()
*   - servant()->format()->path()
*   - servant()->paths()->root()
*   - servant()->site()
*   - servant()->utilities()->load()
*/
class ServantPage extends ServantObject {

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

	// Location of this page relative to its siblings
	protected function setIndex () {
		$siblings = array_flip($this->siblings());
		return $this->set('index', $siblings[$this->id()]);
	}

	// Depth of this page in site's page tree
	protected function setLevel () {
		return $this->set('level', count($this->tree()));
	}

	// Human-readable name, generated from ID
	protected function setName () {
		return $this->set('name', $this->servant()->format()->pageName($this->id()));
	}

	protected function setOutput () {
		$urlManipulator = new UrlManipulator();

		// Root path for src attributes
		$srcUrl = $this->site()->path('domain');

		// Root path for hrefs
		$hrefUrl = $this->servant()->paths()->root('domain').$this->servant()->action()->id().'/';

		// Relative location for SRC urls
		$relativeSrcUrl = unprefix(dirname($this->path('plain')), $this->site()->path('plain'), true);
		if (!empty($relativeSrcUrl)) {
			$relativeSrcUrl .= '/';
		}

		// Relative location for HREF urls
		$relativeHrefUrl = implode('/', array_reverse($this->parents()));
		if (!empty($relativeHrefUrl)) {
			$relativeHrefUrl .= '/';
		}

		// Base URL to point to actions on the domain
		$actionsUrl = $this->servant()->paths()->root('domain');

		return $this->set('output', $urlManipulator->htmlUrls($this->servant()->files()->read($this->path('server')), $srcUrl, $relativeSrcUrl, $hrefUrl, $relativeHrefUrl, $actionsUrl));
	}

	// Parent nodes of this page in site's page tree, order is reversed
	protected function setParents () {
		$parents = array_reverse($this->tree());
		array_shift($parents);
		return $this->set('parents', $parents);
	}

	// Site scripts relevant to this page
	protected function setScripts () {
		return $this->set('scripts', $this->filterSiteFiles('scripts'));
	}

	// All pages on this level of the site page tree. Includes this page.
	protected function setSiblings () {
		$siblings = array_keys($this->site()->pages(array_reverse($this->parents())));
		return $this->set('siblings', empty($siblings) ? array() : $siblings);
	}

	protected function setSite ($site = null) {

		// Fall back to primary site
		if (!$site or get_class($site) !== 'ServantSite') {
			$site = $this->servant()->site();
		}

		return $this->set('site', $site);
	}

	// Site stylesheet relevant to this page
	protected function setStylesheets () {
		return $this->set('stylesheets', $this->filterSiteFiles('stylesheets'));
	}

	protected function setTree ($treeInput = null) {
		$resultTree = $this->selectPage($this->site()->pages(), to_array($treeInput));
		return $this->set('tree', $resultTree);
	}

	protected function setType () {
		return $this->set('type', pathinfo($this->path(), PATHINFO_EXTENSION));
	}

	protected function setPath () {
		return $this->set('path', $this->site()->pages($this->tree()));
	}



	// Private helpers

	// Choose one page from those available, preferring the one detailed in $tree
	private function selectPage ($pagesOnThisLevel, $tree, $level = 0) {
 
		// No preference or preferred item doesn't exist: auto select
		if (!isset($tree[$level]) or !array_key_exists($tree[$level], $pagesOnThisLevel)) {

			// Cut out the rest of the preferred items
			$tree = array_slice($tree, 0, $level);

			// Auto select first item on this level
			$keys = array_keys($pagesOnThisLevel);
			$tree[] = $keys[0];

		}

		// We need to go deeper
		if (is_array($pagesOnThisLevel[$tree[$level]])) {
			return $this->selectPage($pagesOnThisLevel[$tree[$level]], $tree, $level+1);

		// That was it
		} else {
			return array_slice($tree, 0, $level+1);
		}

	}

	// Select the files from site() that are relevant for this page
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

		// Traverse site's files, accept the ones on allowed levels
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