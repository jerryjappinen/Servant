<?php

/**
* A traversable node with potential for parents or children
*
* FLAG
*   - Could be made looser (no template needed, no parent needed)
*	- node, page and category could probably be fused into one node class
*
* DEPENDENCIES
*   ???
*/
class ServantNode extends ServantObject {

	/**
	* Properties
	*/
	protected $propertyBrowserCache 		= null;
	protected $propertyDepth 				= null;
	protected $propertyDescription 			= null;
	protected $propertyIcon 				= null;
	protected $propertyId 					= null;
	protected $propertyIndex 				= null;
	protected $propertyLanguage 			= null;
	protected $propertyName 				= null;
	protected $propertyParent 				= null;
	protected $propertyPointer 				= null;
	protected $propertyServerCache 			= null;
	protected $propertySiteName 			= null;
	protected $propertySplashImage 			= null;
	protected $propertyTemplate 			= null;

	protected $propertyExternalScripts 		= null;
	protected $propertyExternalStylesheets 	= null;



	/**
	* Traversal
	*/

	public function isRoot () {
		return $this->parents(true) ? false : true;
	}

	// Next sibling
	public function next () {
		$result = false;
		$target = $this->sibling($this->index()+1);
		if ($target) {
			$result = $target;
		}
		return $result;
	}

	// Parent nodes
	public function parents ($includeRoot = false) {
		$arguments = func_get_args();
		$parents = array();

		// Inherit grandparents
		$parent = $this->parent();
		if ($parent) {
			$parents = $parent->parents(true);
			$parents[] = $parent;
		}

		// FLAG this behavior is a bit odd, it's a hacky solution
		if (is_bool($includeRoot)) {
			array_shift($arguments);
		}
		if ($includeRoot === false) {
			array_shift($parents);
		}

		// Traverse parents
		return array_traverse($parents, $arguments);
	}

	// Previous sibling
	public function previous () {
		$result = false;
		$target = $this->sibling($this->index()-1);
		if ($target) {
			$result = $target;
		}
		return $result;
	}

	// Root node
	public function root () {
		return $this->isRoot() ? $this : $this->parents(true, 0);
	}

	// Any sibling by index
	public function sibling () {
		$arguments = func_get_args();
		return array_traverse($this->siblings(), $arguments);
	}

	// All siblings, including self
	public function siblings () {
		$arguments = func_get_args();
		return $this->parent() ? call_user_func_array(array($this->parent(), 'children'), $arguments) : array();
	}

	// Pointer as string
	public function stringPointer ($includeRoot = false) {
		return implode('/', $this->pointer($includeRoot));
	}

	// Parents + self
	public function tree ($includeRoot = false) {
		$nodes = $this->parents($includeRoot);
		$nodes[] = $this;
		return $nodes;
	}



	/**
	* Getters
	*/

	public function browserCache () {
		return $this->getAndSet('browserCache');
	}

	public function depth ($includeRoot = false) {
		$depth = $this->getAndSet('depth');
		return $includeRoot ? $depth : $depth-1;
	}

	public function description () {
		$description = $this->getAndSet('description');

		// Bubble
		if (empty($description)) {

			// Parent
			if ($this->parent()) {
				$description = $this->parent()->description();

			// Global
			} else {
				$description = $this->servant()->site()->description();
			}

		}

		return $description;
	}

	public function externalScripts ($format = false) {

		// Format these paths correctly
		$paths = $this->getAndSet('externalScripts');
		if ($format) {
			foreach ($paths as $key => $path) {
				$paths[$key] = $this->servant()->paths()->format($path, $format);
			}
		}

		// Include paths from parent node
		if ($this->parent()) {
			$parentPaths = $this->parent()->externalScripts($format);
			$paths = array_merge($parentPaths, $paths);
		}

		return $paths;
	}

	public function externalStylesheets ($format = false) {

		// Format these paths correctly
		$paths = $this->getAndSet('externalStylesheets');
		if ($format) {
			foreach ($paths as $key => $path) {
				$paths[$key] = $this->servant()->paths()->format($path, $format);
			}
		}

		// Include paths from parent node
		if ($this->parent()) {
			$parentPaths = $this->parent()->externalStylesheets($format);
			$paths = array_merge($parentPaths, $paths);
		}

		return $paths;
	}

	public function icon ($format = false) {
		$icon = $this->getAndSet('icon');

		// Bubble
		if (empty($icon)) {

			// Parent
			if ($this->parent()) {
				$icon = $this->parent()->icon();

			// Global
			} else {
				$icon = $this->servant()->site()->icon();
			}

		}

		if (!empty($icon) and $format) {
			$icon = $this->servant()->paths()->format($icon, $format);
		}

		return $icon;
	}

	public function id () {
		$arguments = func_get_args();
		return $this->getOrSet('id', $arguments);
	}

	public function index () {
		return $this->getAndSet('index');
	}

	public function language () {
		$language = $this->getAndSet('language');

		// Bubble
		if (empty($language)) {

			// Parent
			if ($this->parent()) {
				$language = $this->parent()->language();

			// Global
			} else {
				$language = $this->servant()->site()->language();
			}

		}

		return $language;
	}

	public function name () {
		$arguments = func_get_args();
		return $this->getOrSet('name', $arguments);
	}

	public function parent () {
		return $this->getAndSet('parent');
	}

	public function pointer ($includeRoot = false) {
		$arguments = func_get_args();
		$pointer = $this->getAndSet('pointer');

		// FLAG this behavior is a bit odd, it's a hacky solution
		if (is_bool($includeRoot)) {
			array_shift($arguments);
		}
		if ($includeRoot === false) {
			array_shift($pointer);
		}

		return array_traverse($pointer, $arguments);
	}

	public function serverCache () {
		return $this->getAndSet('serverCache');
	}

	public function siteName () {
		$siteName = $this->getAndSet('siteName');

		// Bubble
		if (empty($siteName)) {

			// Parent
			if ($this->parent()) {
				$siteName = $this->parent()->siteName();

			// Global
			} else {
				$siteName = $this->servant()->site()->name();
			}

		}

		return $siteName;
	}

	public function splashImage ($format = false) {
		$splashImage = $this->getAndSet('splashImage');

		// Bubble
		if (empty($splashImage)) {

			// Parent
			if ($this->parent()) {
				$splashImage = $this->parent()->splashImage();

			// Global
			} else {
				$splashImage = $this->servant()->site()->splashImage();
			}

		}

		if (!empty($splashImage) and $format) {
			$splashImage = $this->servant()->paths()->format($splashImage, $format);
		}

		return $splashImage;
	}

	public function template () {
		$template = $this->getAndSet('template');

		// Bubble
		if (empty($template)) {

			// Parent
			if ($this->parent()) {
				$template = $this->parent()->template();

			// Global
			} else {
				$template = $this->servant()->site()->template();
			}

		}

		return $template;
	}



	/**
	* Setters
	*/

	/**
	* Browser cache in minutes
	*/
	protected function setBrowserCache () {
		$input = $this->servant()->manifest()->browserCaches($this->stringPointer());
		return $this->set('browserCache', $input ? $input : 0);
	}

	/**
	* Depth
	*/
	protected function setDepth () {
		return $this->set('depth', count($this->parents(true)));
	}

	/**
	* Description text
	*/
	protected function setDescription () {
		$input = $this->servant()->manifest()->descriptions($this->stringPointer());
		return $this->set('description', $input ? $input : '');
	}

	/**
	* Node-specific external scripts
	*/
	protected function setExternalScripts () {
		$input = $this->servant()->manifest()->scripts($this->stringPointer());
		return $this->set('externalScripts', $input ? $input : array());
	}

	/**
	* Node-specific external stylesheets
	*/
	protected function setExternalStylesheets () {
		$input = $this->servant()->manifest()->stylesheets($this->stringPointer());
		return $this->set('externalStylesheets', $input ? $input : array());
	}

	/**
	* Icon
	*/
	protected function setIcon () {
		$input = $this->servant()->manifest()->icons($this->stringPointer());
		return $this->set('icon', $input ? $input : '');
	}

	/**
	* ID
	*/
	protected function setId ($input) {

		// Allow overriding auto set
		if (is_string($input)) {
			$input = trim_whitespace($input);
			if (!empty($input)) {
				$id = $input;
			}
		}

		// Default
		if (!isset($id)) {
			$this->fail('Invalid ID passed to node.');
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
	* Language
	*/
	protected function setLanguage () {
		$input = $this->servant()->manifest()->languages($this->stringPointer());
		return $this->set('language', $input ? $input : '');
	}

	/**
	* Human-readable name
	*/
	protected function setName ($input = null) {

		// Allow overriding automatic ID manually
		if (is_string($input)) {
			$input = trim_text($input, true);
			if (!empty($input)) {
				$name = $input;
			}
		}

		// Default
		if (!isset($name)) {

			// Name given in settings
			$input = $this->servant()->manifest()->pageNames($this->stringPointer());
			if ($input) {
				$name = $input;

			// Generate
			} else {
				$conversions = $this->servant()->constants()->namingConvention();
				$name = ucfirst(trim(str_ireplace(array_keys($conversions), array_values($conversions), $this->id())));
			}

		}

		return $this->set('name', $name);
	}

	/**
	* Parent node
	*/
	protected function setParent ($category) {

		// Make sure the parent is a category
		if ($this->getServantClass($category) !== 'category') {
			$this->fail('Pages need a category parent to take care of them.');
		}

		// FLAG this behavior isn't very clear...
		$category->addChildren($this);

		return $this->set('parent', $category);
	}

	/**
	* List of parent IDs + own ID
	*/
	protected function setPointer () {
		$results = array();
		foreach ($this->parents(true) as $parent) {
			$results[] = $parent->id();
		}
		$results[] = $this->id();
		return $this->set('pointer', $results);
	}

	/**
	* Server cache in minutes
	*/
	protected function setServerCache () {
		$input = $this->servant()->manifest()->serverCaches($this->stringPointer());
		return $this->set('serverCache', $input ? $input : 0);
	}

	/**
	* Site name
	*
	* NOTE
	*	- Servant supports setting a specific site name for any sitemap node and its children.
	*	- Site name should always be accessed via a node object
	*	- ServantSite's name property is only a default.
	*/
	protected function setSiteName () {
		$input = $this->servant()->manifest()->siteNames($this->stringPointer());
		return $this->set('siteName', $input ? $input : '');
	}

	/**
	* SplashImage
	*/
	protected function setSplashImage () {
		$input = $this->servant()->manifest()->splashImages($this->stringPointer());
		return $this->set('splashImage', $input ? $input : '');
	}

	/**
	* Template
	*/
	protected function setTemplate () {
		$template = '';

		// Template defined in settings
		$input = $this->servant()->manifest()->templates($this->stringPointer());
		if ($input) {
			if ($this->servant()->available()->template($input)) {
				$template = $input;

			// Template not available
			} else {
				$this->warn('Missing template "'.$template.'" for '.$this->stringPointer().'.');

			}

		}

		return $this->set('template', $template);
	}

}

?>