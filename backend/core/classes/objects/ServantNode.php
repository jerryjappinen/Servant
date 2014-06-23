<?php

/**
* A traversable node with potential for parents or children
*
* FLAG
*   - Get templates from manifest
*   - Get page name from manifest
*
*   - Add site name, get it from manifest
*   - Add icon, get it from manifest
*   - Add splash image, get it from manifest
*   - Add external scripts, get it from manifest
*   - Add external stylesheets, get it from manifest
*
* DEPENDENCIES
*   ???
*/
class ServantNode extends ServantObject {

	/**
	* Properties
	*/
	protected $propertyDepth 		= null;
	protected $propertyDescription 	= null;
	protected $propertyId 			= null;
	protected $propertyIndex 		= null;
	protected $propertyName 		= null;
	protected $propertyParent 		= null;
	protected $propertyPointer 		= null;
	protected $propertyTemplate 	= null;



	/**
	* Convenience
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

	public function id () {
		$arguments = func_get_args();
		return $this->getOrSet('id', $arguments);
	}

	public function index () {
		return $this->getAndSet('index');
	}

	public function name () {
		$arguments = func_get_args();
		return $this->getOrSet('name', $arguments);
	}

	public function parent () {
		return $this->getAndSet('parent');
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

		// Get settings
		$pageDescriptions = $this->servant()->site()->pageDescriptions();
		$pointer = $this->stringPointer();

		// Description defined in settings
		if (array_key_exists($pointer, $pageDescriptions)) {
			$description = trim_text($pageDescriptions[$pointer]);
		}

		return $this->set('description', $description);
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
			$replacements = $this->servant()->site()->pageNames();
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
	* Template
	*/
	protected function setTemplate () {
		$template = '';

		// Get settings
		$pageTemplates = $this->servant()->site()->pageTemplates();
		$pointer = $this->stringPointer();

		// Template defined in settings
		if (array_key_exists($pointer, $pageTemplates)) {
			if ($this->servant()->available()->template($pageTemplates[$pointer])) {
				$template = $pageTemplates[$pointer];

			// Template not available
			} else {
				$this->warn('Missing template "'.$template.'" for '.$pointer.'.');

			}

		}

		return $this->set('template', $template);
	}

}

?>