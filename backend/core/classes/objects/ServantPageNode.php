<?php

/**
* A page
*
* DEPENDENCIES
*   ServantFiles 		-> read
*   ServantFormat 		-> path
*   					-> title
*   ServantPaths 		-> userAction
*   ServantSite 		-> pageNames
*/
class ServantPageNode extends ServantObject {

	/**
	* Properties
	*/

	// Files
	protected $propertyPath 		= null;
	protected $propertyScripts 		= null;
	protected $propertyStylesheets 	= null;

	// Page's own stuff
	protected $propertyName 		= null;
	protected $propertyOutput 		= null;

	// Set automatically
	protected $propertyDepth 		= null;
	protected $propertyHome 		= null;
	protected $propertyId 			= null;
	protected $propertyIndex 		= null;
	protected $propertyReadPath 	= null;
	protected $propertyTree 		= null;
	protected $propertyType 		= null;

	// Other pages (siblings are parent's children)
	protected $propertyChildren 	= null;
	protected $propertyParent 		= null;



	/**
	* Template path is needed upon initialization
	*/
	public function initialize ($relativePath, $parent = null) {

		// Template file needs to be there
		$this->setPath($relativePath);

		// Defaults
		$this->name($this->generatePageName($this->id()));

		// Other pages
		$this->setChildren(array());
		$this->setParent($parent ? $parent : false);

		return $this;
	}



	/**
	* Getter-setters
	*/

	public function name () {
		$arguments = func_get_args();
		return $this->getOrSet('name', $arguments);
	}

	public function output () {
		$arguments = func_get_args();
		return $this->getOrSet('output', $arguments);
	}



	/**
	* Path-style getters
	*/

	public function path ($format = false) {
		$path = $this->getAndSet('path');
		if ($format) {
			$path = $this->servant()->format()->path($path, $format);
		}
		return $path;
	}

	public function readPath ($format = false) {
		$path = $this->getAndSet('readPath');
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
	* Setters needing input
	*/

	/**
	* Human-readable name
	*/
	protected function setName ($input) {
		$input = ''.$input;

		// Fail on invalid input
		if (!is_string($input)) {
			$this->fail('Invalid name given to page.');

		} else {
			return $this->set('name', $input);
		}
	}

	/**
	* Path to the template file
	*/
	protected function setPath ($relativePath) {

		$path = $this->servant()->paths()->pages().unprefix($relativePath, '/');

		// Template file must exist
		if (!is_file($this->servant()->format()->path($path, 'server'))) {
			$this->fail('Non-existing template file given to page.');
		}

		return $this->set('path', $path);
	}



	/**
	* Auto setters
	*/

	protected function setDepth () {
		return $this->set('depth', count($this->parents()));
	}

	// Is this the home page? Home page is always the root.
	protected function setHome () {
		return $this->set('home', $this->depth() === 0 ? true : false);
	}

	protected function setId () {
		return $this->set('id', pathinfo($this->path(), PATHINFO_FILENAME));
	}

	// Location of this page relative to its siblings
	protected function setIndex () {
		$result = 0;
		foreach ($this->siblings() as $i => $sibling) {
			if ($sibling === $this) {
				$result = $i;
				break;
			}
		}
		return $this->set('index', $result);
	}

	// Path to this page in read action
	protected function setReadPath () {
		$action = $this->servant()->settings()->actions('read');
		return $this->set('readPath', $this->servant()->paths()->userAction($action, 'plain', $this->tree()));
	}

	protected function setTree () {
		$results = $this->listParents('id');
		array_push($results, $this->id());
		return $this->set('tree', $results);
	}

	protected function setType () {
		return $this->set('type', pathinfo($this->path(), PATHINFO_EXTENSION));
	}



	/**
	* Dumb setters
	*/

	/**
	* Return template content as a string
	*/
	protected function setOutput () {

		// Read content from source file
		$fileContent = $this->servant()->files()->read($this->path('server'), array(
			'servant' => $this->servant(),
			'page' => $this,
		));

		// Save file content
		return $this->set('output', $fileContent);
	}

	/**
	* Paths to script files under pages, relevant to this page
	*/
	protected function setScripts () {
		return $this->set('scripts', $this->filterPageFiles('scripts'));
	}

	/**
	* Paths to stylesheet files under pages, relevant to this page
	*/
	protected function setStylesheets () {
		return $this->set('stylesheets', $this->filterPageFiles('stylesheets'));
	}



	/**
	* Children
	*/
	protected function setChildren ($pages) {
		return $this->set('children', $pages);
	}
	public function listChildren ($property = null) {
		return $this->listPageProperties($this->children(), $property);
	}

	// Adding child page(s)
	public function addChild ($pageOrPath) {
		$newPages = func_get_args();
		$newPages = array_flatten($newPages);

		foreach ($newPages as $key => $newPage) {

			// Generate new page objects if needed
			if ($this->getServantClass($newPage) !== 'pageNode') {
				$newPages[$key] = $this->generate('pageNode', $newPage);
			}

			// Set self as parent
			// FLAG requires setParent to be public
			$newPages[$key]->setParent($this);

		}

		return $this->setChildren(array_merge($this->children(), $newPages));
	}



	/**
	* Parent(s)
	*/

	// FLAG should probably not be public...
	public function setParent ($page = null) {
		$result = false;
		if ($this->getServantClass($page) === 'pageNode') {
			$result = $page;
		}
		return $this->set('parent', $result);
	}

	public function parents () {
		$parents = array();

		// Has valid parent
		if ($this->parent()) {
			$parent = $this->parent();
			$parents = $parent->parents();
			$parents[] = $parent;
		}

		return $parents;
	}

	public function listParents ($property = null) {
		return $this->listPageProperties($this->parents(), $property);
	}



	/**
	* Convenience
	*/
	public function sibling () {
		$arguments = func_get_args();
		return $this->home() ? null : array_traverse($this->siblings(), $arguments);
	}
	public function siblings () {
		return $this->home() ? array() : $this->parent()->children();
	}
	public function listSiblings ($property = null) {
		$arguments = func_get_args();
		return $this->home() ? array() : call_user_func_array(array($this->parent(), 'listChildren'), $arguments);
	}
	public function addSibling ($pageOrPath) {
		if ($this->home()) {
			$this->fail('The home page does not have siblings.');
		} else {
			$arguments = func_get_args();
			return call_user_func_array(array($this->parent(), 'addChild'), $arguments);
		}
	}



	/**
	* Private helpers
	*/

	private function listPageProperties ($pageArray, $property = null) {
		$results = array();
		if (!$property) {
			$property = 'id';
		}
		foreach ($pageArray as $page) {
			$results[] = $page->$property();
		}
		return $results;
	}

	/**
	* Select the files under pages that are relevant for this page (i.e. stylesheets or scripts in parent folders)
	*/
	private function filterPageFiles ($formatType) {

		// Origin directories
		$pagesDir = $this->servant()->paths()->pages('server');
		$dirs = array_filter(explode('/', unprefix(dirname($this->path('server')).'/', $pagesDir)));
		array_unshift($dirs, '');

		// Compose paths for valid parent directories
		for ($i = 1; $i < count($dirs); $i++) { 
			$dirs[$i] = $dirs[$i-1].$dirs[$i].'/';
		}
		unset($i);

		// List files in the directories
		$files = array();
		foreach ($dirs as $dir) {
			$dir = $pagesDir.$dir;
			$files = array_merge($files, glob_files($dir, $this->servant()->settings()->formats($formatType)));
		}

		return $files;
	}

	/**
	* Generate human-readable title for page from string
	*/
	private function generatePageName ($string) {
		$name = $string;

		// Explicit names given
		$replacements = $this->servant()->site()->pageNames();
		$key = mb_strtolower($string);
		if ($replacements and is_array($replacements) and array_key_exists($key, $replacements)) {
			$name = $replacements[$key];

		// Generate
		} else {
			$name = $this->servant()->format()->title($string);
		}

		return $name;
	}

}

?>