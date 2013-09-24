<?php

/**
* Page component
*
* Dependencies
*   - servant()->action()->id()
*   - servant()->files()->read()
*   - servant()->format()->pageName()
*   - servant()->format()->path()
*   - servant()->paths()->root()
*   - servant()->utilities()->load()
*/
class ServantPage extends ServantObject {

	/**
	* Properties
	*/
	protected $propertyId 			= null;
	protected $propertyIndex 		= null;
	protected $propertyIsCurrent 	= null;
	protected $propertyLevel 		= null;
	protected $propertyName 		= null;
	protected $propertyOutput 		= null;
	protected $propertyPages 		= null;
	protected $propertyPath 		= null;
	protected $propertyParents 		= null;
	protected $propertyScripts 		= null;
	protected $propertySiblings 	= null;
	protected $propertyStylesheets 	= null;
	protected $propertyTree 		= null;
	protected $propertyType 		= null;



	/**
	* Select ID when initializing
	*/
	public function initialize ($pages, $tree = null) {

		// Load utilities
		$this->servant()->utilities()->load('urls');

		// Required items
		$this->setPages($pages);

		// Generate tree
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
	*   - ->pages() and ->tree() determine most of these
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

	// Is this the current page?
	protected function setIsCurrent () {
		return $this->set('isCurrent', $this->pages()->current() === $this);
	}

	// Depth of this page in the page tree (starts from 1)
	protected function setLevel () {
		return $this->set('level', (count($this->tree()) -1));
	}

	// Human-readable name, generated from ID
	protected function setName () {
		$id = $this->id();

		// Use category as name
		if ($this->index() === 0 and $this->level() > 0) {
			$parents = $this->parents();
			$id = end($parents);
		}

		return $this->set('name', $this->servant()->format()->pageName($id));
	}

	// FLAG should the manipulation be done in read action? That's how it is for stylesheets, too
	protected function setOutput () {
		$urlManipulator = new UrlManipulator();

		// Root path for src attributes
		$srcUrl = $this->pages()->path('domain');

		// Root path for hrefs
		$hrefUrl = $this->servant()->paths()->root('domain').$this->servant()->action()->id().'/';

		// Relative location for SRC urls
		$relativeSrcUrl = unprefix(dirname($this->path('plain')), $this->pages()->path('plain'), true);
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

		// Read content from source file
		$fileContent = $this->servant()->files()->read($this->path('server'));

		// Save file content with manipulated URLs
		return $this->set('output', $urlManipulator->htmlUrls($fileContent, $srcUrl, $relativeSrcUrl, $hrefUrl, $relativeHrefUrl, $actionsUrl));
	}

	// ServantPages object
	protected function setPages ($pages) {
		return $this->set('pages', $pages);
	}

	// Parent nodes of this page in the page tree, order is reversed
	protected function setParents () {
		$parents = array_reverse($this->tree());
		array_shift($parents);
		return $this->set('parents', $parents);
	}

	// Scripts under pages relevant to this page
	protected function setScripts () {
		return $this->set('scripts', $this->filterPageFiles('scripts'));
	}

	// All pages on this level of the page tree. Includes this page.
	protected function setSiblings () {
		$siblings = array_keys($this->pages()->templates(array_reverse($this->parents())));
		return $this->set('siblings', empty($siblings) ? array() : $siblings);
	}

	// Stylesheets under pages relevant to this page
	protected function setStylesheets () {
		return $this->set('stylesheets', $this->filterPageFiles('stylesheets'));
	}

	protected function setTree ($tree = array()) {

		// No source file, so we can't really do this
		if (!$this->pages()->templates($tree)) {
			$this->fail('This page does not exist');
		}

		return $this->set('tree', $tree);
	}

	protected function setType () {
		return $this->set('type', pathinfo($this->path(), PATHINFO_EXTENSION));
	}

	protected function setPath () {
		return $this->set('path', $this->pages()->templates($this->tree()));
	}



	/**
	* Private helpers
	*/

	// Select the files under pages that are relevant for this page (i.e. stylesheets or scripts)
	private function filterPageFiles ($type) {
		$results = array();

		// FLAG legacy, don't need to traverse all files
		$formats = $this->servant()->settings()->formats($type);
		$allFiles = array();
		foreach (rglob_files($this->pages()->path('server'), $formats) as $file) {
			$allFiles[] = $this->servant()->format()->path($file, false, 'server');
		}

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

		// Traverse all files, accept the ones on allowed levels
		foreach ($allFiles as $value) {
			$base = unprefix(dirname($value).'/', $this->pages()->path(), true);
			if (in_array($base, $allowed)) {
				$results[] = $value;
			}
		}

		return $results;
	}

}

?>