<?php

/**
* A holder node with children
*/
class ServantCategoryNode extends ServantNode {

	/**
	* Properties
	*/
	protected $propertyChildren 	= null;



	/**
	* Convenience
	*/

	public function category () {
		return true;
	}

	public function endpoint ($format = false) {
		return $this->masterPage()->endpoint($format);
	}

	public function masterPage () {
		return $this->children(0);
	}

	public function page () {
		return false;
	}



	/**
	* Allow passing child pages in init
	*/

	public function initialize ($id, $parent = null) {
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
	* Children
	*/

	protected function setChildren ($pages = array()) {
		return $this->set('children', $pages);
	}

	// Adding child page(s)
	public function addChildren ($pages = array()) {
		$pages = func_get_args();
		$pages = array_flatten($pages);

		// Validate pages
		foreach ($pages as $key => $page) {
			if (!in_array($this->getServantClass($page), array('pageNode', 'categoryNode'))) {
				$this->fail('Invalid child passed to '.$this->name().' category.');
			}
		}

		return $this->setChildren(array_merge($this->children(), $pages));
	}

}

?>