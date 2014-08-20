<?php

/**
* A holder node with children
*
* NOTE
*   - User does not usually know if she has a category or page node
*
* DEPENDENCIES
*   ???
*/
class ServantCategory extends ServantNode {

	/**
	* Properties
	*/
	protected $propertyChildren 	= null;



	/**
	* Convenience API for categories
	*/

	// All child (recursive) pages as a flat array
	public function allPages () {
		$arguments = func_get_args();

		// Find child nodes
		$pages = $this->pages();
		foreach ($this->categories() as $category) {
			$pages = array_merge($pages, $category->allPages());
		}

		return array_traverse($pages, $arguments);
	}

	// Direct child categories
	public function categories () {
		$arguments = func_get_args();

		// Find subcategories
		$categories = array();
		foreach ($this->children() as $node) {
			if ($node->isCategory()) {
				$categories[] = $node;
			}
		}

		return array_traverse($categories, $arguments);
	}

	public function category () {
		return $this;
	}

	public function isCategory () {
		return true;
	}

	public function isHome () {
		return false;
	}

	public function isPage () {
		return false;
	}

	public function page () {
		$child = $this->children(0);
		if ($child->isCategory()) {
			$child = $child->page();
		}
		return $child;
	}

	// Direct child pages
	public function pages () {
		$arguments = func_get_args();

		// Find child nodes
		$pages = array();
		foreach ($this->children() as $node) {
			if ($node->isPage()) {
				$pages[] = $node;
			}
		}

		return array_traverse($pages, $arguments);
	}



	/**
	* Page-like behavior
	*/

	public function endpoint ($format = false) {
		return $this->page()->endpoint($format);
	}

	public function output () {
		$arguments = func_get_args();
		return call_user_func_array(array($this->page(), 'output'), $arguments);
	}

	public function scripts () {
		return array();
	}

	public function stylesheets () {
		return array();
	}



	/**
	* Initialization
	*/

	protected function initialize ($id, $parent = null) {
		$this->setId($id)->setChildren();

		if ($parent) {
			$this->setParent($parent);
		}

		// Support creating children while we're at it
		$children = func_get_args();
		call_user_func_array(array($this, 'addChildren'), array_flatten(array_slice($children, 2)));

		return $this;
	}



	/**
	* Getters
	*/

	public function children () {
		$arguments = func_get_args();
		return $this->getAndSet('children', $arguments);
	}



	/**
	* Setters
	*/

	protected function setChildren ($pages = array()) {
		return $this->set('children', $pages);
	}

	protected function setParent ($parent = null) {

		// False is for root categories
		$category = false;

		// Input given
		if ($parent !== null) {

			if ($this->getServantClass($parent) === 'category') {
				$category = $parent;

				// Add this page object to the parent's list of children
				// FLAG this behavior isn't very clear...
				$category->addChildren($this);

			} else {
				$this->fail('Pages need a category parent to take care of them.');
			}

		}


		return $this->set('parent', $category);
	}



	/**
	* Helpers
	*/

	// Adding child page(s)
	public function addChildren ($pages = array()) {
		$pages = func_get_args();
		$pages = array_flatten($pages);

		// Validate pages
		foreach ($pages as $key => $page) {
			if (!in_array($this->getServantClass($page), array('page', 'category'))) {
				$this->fail('Invalid child passed to '.$this->name().' category.');
			}
		}

		return $this->setChildren(array_merge($this->children(), $pages));
	}

}

?>