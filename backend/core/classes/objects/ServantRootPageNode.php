<?php

/**
* A mock page object, used as root of the page tree
*/
class ServantRootPageNode extends ServantObject {

	/**
	* Properties
	*/
	protected $propertyChildren 	= null;



	/**
	* Convenience
	*/

	public function parents () {
		return array();
	}



	/**
	* Allow passing child pages in init
	*/

	public function initialize ($children = array()) {
		$this->setChildren(array());

		// Support creating children while we're at it
		$children = func_get_args();
		call_user_func_array(array($this, 'addChildren'), array_flatten($children));

		return $this;
	}

	public function root () {
		return true;
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

}

?>