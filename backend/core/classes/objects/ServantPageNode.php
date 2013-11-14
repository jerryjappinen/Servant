<?php

/**
* A page
*
* FLAG
*   - Should inherit rootPageNode?
*   - Could be made looser (no template needed, no parent needed)
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
	protected $propertyCategoryId 	= null;
	protected $propertyDepth 		= null;
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
	public function initialize ($path, $parent) {

		// Template file needs to be there
		$this->setPath($path);

		// Other pages
		$this->setParent($parent);

		return $this;
	}

	public function root () {
		return false;
	}



	/**
	* Getter-setters
	*/

	public function categoryId () {
		$arguments = func_get_args();
		return $this->getOrSet('categoryId', $arguments);
	}

	public function id () {
		$arguments = func_get_args();
		return $this->getOrSet('id', $arguments);
	}

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
	* Setters
	*/

	/**
	* Category ID
	*/
	protected function setCategoryId ($input = null) {

		// Allow overriding automatic ID manually
		if (is_string($input)) {
			$input = trim_whitespace($input);
			if (!empty($input)) {
				$id = $input;
			}
		}

		// Default
		if (!isset($id)) {

			// Dirname
			$dir = basename(unprefix(dirname($this->path()), $this->servant()->paths()->pages()));
			if ($dir) {
				$id = $dir;

			// ID is category ID, if nothing else is
			} else {
				$id = $this->id();
			}
		}

		return $this->set('categoryId', $id);
	}

	/**
	* Depth
	*/
	protected function setDepth () {
		return $this->set('depth', count($this->parents()));
	}

	/**
	* ID
	*/
	protected function setId ($input = null) {

		// Allow overriding automatic ID manually
		if (is_string($input)) {
			$input = trim_whitespace($input);
			if (!empty($input)) {
				$id = $input;
			}
		}

		// Default
		if (!isset($id)) {
			$id = pathinfo($this->path(), PATHINFO_FILENAME);
		}

		return $this->set('id', $id);
	}

	/**
	* Location of this page relative to its siblings
	*/
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

	/**
	* Human-readable name
	*/
	protected function setName ($input = null) {

		// Allow overriding automatic ID manually
		if (is_string($input)) {
			$input = trim_text($input, true);
			if (!empty($input)) {
				$id = $input;
			}
		}

		// Default
		if (!isset($name)) {
			$name = $this->generatePageName($this->id());
		}

		return $this->set('name', $name);
	}

	/**
	* Template content as a string
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
	* Path to the template file
	*/
	protected function setPath ($path) {

		// Template file must exist
		if (!is_file($this->servant()->format()->path($path, 'server'))) {
			$this->fail('Non-existing template file given to page ("'.$path.'").');
		}

		return $this->set('path', $path);
	}

	// Path to this page in read action
	protected function setReadPath () {
		$action = $this->servant()->settings()->actions('read');
		return $this->set('readPath', $this->servant()->paths()->userAction($action, 'plain', $this->tree()));
	}

	// Paths to script files under pages, relevant to this page
	protected function setScripts () {
		return $this->set('scripts', $this->filterPageFiles('scripts'));
	}

	// Paths to stylesheet files under pages, relevant to this page
	protected function setStylesheets () {
		return $this->set('stylesheets', $this->filterPageFiles('stylesheets'));
	}

	// List of parent IDs + own ID
	protected function setTree () {
		$results = $this->listParents('id');
		array_push($results, $this->id());
		return $this->set('tree', $results);
	}

	// Template file type
	protected function setType () {
		return $this->set('type', pathinfo($this->path(), PATHINFO_EXTENSION));
	}



	/**
	* Children
	*/
	protected function setChildren ($pages = array()) {
		return $this->set('children', $pages);
	}
	public function listChildren ($property = null) {
		return $this->listPageProperties($this->children(), $property);
	}

	// Adding child page(s)
	public function addChildren ($pages = array()) {
		$pages = func_get_args();
		$pages = array_flatten($pages);

		// Validate pages
		foreach ($pages as $key => $page) {
			if ($this->getServantClass($page) !== 'pageNode') {
				$this->fail('Invalid page object passed to root page.');
			}
		}

		return $this->setChildren(array_merge($this->children(), $pages));
	}



	/**
	* Parent(s)
	*/

	protected function setParent ($page) {
		if (!in_array($this->getServantClass($page), array('pageNode', 'rootPageNode'))) {
			$this->fail('Pages need a valid parent to take care of them (create a rootPageNode to act as a parent if needed).');
		}
		$page->addChildren($this);
		return $this->set('parent', $page);
	}

	public function parents () {
		$parents = array();
		$parent = $this->parent();

		// Inherit grandparents
		if (!$parent->root()) {
			$parents = $parent->parents();
			$parents[] = $parent;
		}

		return $parents;
	}

	public function listParents ($property = null) {
		return $this->listPageProperties($this->parents(), $property);
	}



	/**
	* Sibling convenience methods
	*/
	public function siblings () {
		return $this->parent()->children();
	}
	public function sibling () {
		$arguments = func_get_args();
		return array_traverse($this->siblings(), $arguments);
	}
	public function listSiblings ($property = null) {
		$arguments = func_get_args();
		return call_user_func_array(array($this->parent(), 'listChildren'), $arguments);
	}
	public function addSibling ($page) {
		$arguments = func_get_args();
		return call_user_func_array(array($this->parent(), 'addChildren'), $arguments);
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
			foreach (glob_files($dir, $this->servant()->settings()->formats($formatType)) as $file) {
				$files[] = $this->servant()->format()->path($file, false, 'server');
			}
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