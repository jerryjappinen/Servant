<?php

/**
* A traversable node with potential for parents or children
*
* FLAG
*	- scripts and stylesheets should be under node
*   - Could be made looser (no template needed, no parent needed)
*	- node, page and category could probably be fused into one node class
*
*   - Could we replace ServantSite stuff with root node's values?
*
* DEPENDENCIES
*   ???
*/
class ServantNode extends ServantObject {

	/**
	* Properties
	*/
	protected $propertyDepth 				= null;
	protected $propertyDescription 			= null;
	protected $propertyIcon 				= null;
	protected $propertyId 					= null;
	protected $propertyIndex 				= null;
	protected $propertyLanguage 			= null;
	protected $propertyName 				= null;
	protected $propertyParent 				= null;
	protected $propertyPointer 				= null;
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
		return call_user_func_array(array($this->parent(), 'children'), $arguments);
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
	* Depth
	*/
	protected function setDepth () {
		return $this->set('depth', count($this->parents(true)));
	}

	/**
	* Description text
	*/
	protected function setDescription () {
		$description = '';

		// Get from manifest
		$descriptions = $this->servant()->manifest()->removeRootNodeValue($this->servant()->manifest()->descriptions());
		$pointer = $this->stringPointer();

		// Value defined in settings
		if (array_key_exists($pointer, $descriptions)) {
			$description = $descriptions[$pointer];
		}

		return $this->set('description', $description);
	}

	/**
	* Node-specific external scripts
	*/
	protected function setExternalScripts () {
		$result = array();
		$paths = $this->servant()->manifest()->removeRootNodeValue($this->servant()->manifest()->scripts());
		$stringPointer = $this->stringPointer();

		// Items defined in settings
		if (array_key_exists($stringPointer, $paths)) {
			$result = $paths[$stringPointer];
		}

		return $this->set('externalScripts', $result);
	}

	/**
	* Node-specific external stylesheets
	*/
	protected function setExternalStylesheets () {
		$result = array();
		$paths = $this->servant()->manifest()->removeRootNodeValue($this->servant()->manifest()->stylesheets());
		$stringPointer = $this->stringPointer();

		// Items defined in settings
		if (array_key_exists($stringPointer, $paths)) {
			$result = $paths[$stringPointer];
		}

		return $this->set('externalStylesheets', $result);
	}

	/**
	* Icon
	*/
	protected function setIcon () {
		$icon = '';

		// Get from manifest
		$icons = $this->servant()->manifest()->removeRootNodeValue($this->servant()->manifest()->icons());
		$pointer = $this->stringPointer();

		// Value defined in settings
		if (array_key_exists($pointer, $icons)) {
			$icon = $icons[$pointer];
		}

		return $this->set('icon', $icon);
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
		$language = '';

		// Get from manifest
		$languages = $this->servant()->manifest()->removeRootNodeValue($this->servant()->manifest()->languages());
		$pointer = $this->stringPointer();

		// Value defined in settings
		if (array_key_exists($pointer, $languages)) {
			$language = $languages[$pointer];
		}

		return $this->set('language', $language);
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
			$replacements = $this->servant()->manifest()->removeRootNodeValue($this->servant()->manifest()->pageNames());
			$key = $this->stringPointer();
			if (array_key_exists($key, $replacements)) {
				$name = $replacements[$key];

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
	* Site name
	*
	* NOTE
	*	- Servant supports setting a specific site name for any sitemap node and its children.
	*	- Site name should always be accessed via a node object
	*	- ServantSite's name property is only a default.
	*/
	protected function setSiteName () {
		$siteName = '';

		// Get from manifest
		$siteNames = $this->servant()->manifest()->removeRootNodeValue($this->servant()->manifest()->siteNames());
		$pointer = $this->stringPointer();

		// Value defined in settings
		if (array_key_exists($pointer, $siteNames)) {
			$siteName = $siteNames[$pointer];
		}

		return $this->set('siteName', $siteName);
	}

	/**
	* SplashImage
	*/
	protected function setSplashImage () {
		$splashImage = '';

		// Get from manifest
		$splashImages = $this->servant()->manifest()->removeRootNodeValue($this->servant()->manifest()->splashImages());
		$pointer = $this->stringPointer();

		// Value defined in settings
		if (array_key_exists($pointer, $splashImages)) {
			$splashImage = $splashImages[$pointer];
		}

		return $this->set('splashImage', $splashImage);
	}

	/**
	* Template
	*/
	protected function setTemplate () {
		$template = '';

		// Get from manifest
		$templates = $this->servant()->manifest()->removeRootNodeValue($this->servant()->manifest()->templates());
		$pointer = $this->stringPointer();

		// Template defined in settings
		if (array_key_exists($pointer, $templates)) {
			if ($this->servant()->available()->template($templates[$pointer])) {
				$template = $templates[$pointer];

			// Template not available
			} else {
				$this->warn('Missing template "'.$template.'" for '.$pointer.'.');

			}

		}

		return $this->set('template', $template);
	}

}

?>