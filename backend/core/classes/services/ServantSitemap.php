<?php

/**
* Sitemap with a root category node
*/
class ServantSitemap extends ServantObject {

	/**
	* Properties
	*/
	protected $propertyRoot = null;



	/**
	* Convenience
	*/
	public function pages () {
		$arguments = func_get_args();
		return $this->root()->children($arguments);
	}

	public function select ($tree = array()) {
		$tree = func_get_args();
		return $this->selectNode($tree, $this->root());
	}

	/**
	* Choose one page node from all available nodes, preferring the one pinpointed to in $tree by ID
	*
	* FLAG
	*   - This works with sitemap-generated robust sitemap, but not very dynamically with arbitrary node maps
	*/
	public function selectNode ($tree, $parent) {
		$tree = array_flatten(to_array($tree));

		// Will always return a node, the current one by default
		$result = $parent;

		// List available nodes
		$nodes = array();
		foreach ($parent->children() as $node) {
			$nodes[mb_strtolower($node->id())] = $node;
		}
		unset($node);

		// We must have nodes to traverse
		if (!empty($nodes)) {

			// Extract next item from tree
			$nextId = mb_strtolower(array_shift($tree));

			// No preference or preferred item doesn't exist: auto select
			if (!array_key_exists($nextId, $nodes)) {
				foreach ($nodes as $key => $value) {
					$nextId = $key;
					break;
				}
				unset($key, $value);
			}

			// Select next node
			$nextNode = $nodes[$nextId];

			// We have a category
			if ($nextNode->children() and !empty($tree)) {
				$result = $this->selectNode($tree, $nextNode);
			} else {
				$result = $nextNode;
			}

		}

		return $result;
	}



	/**
	* Initialization
	*/
	public function initialize ($path = null) {

		// Get user input form manifest
		$manifest = $this->servant()->manifest()->sitemap();

		// Generate page order
		$pageOrder = array();
		foreach ($manifest as $stringPointer) {
			$section = substr($stringPointer, 0, strrpos($stringPointer, '/'));
			$pageOrder['root'.($section ? '/'.$section : '')][] = unprefix($stringPointer, $section.'/');
		}

		// Nodes
		$this->generateNodes(
			$this->findPageTemplates($this->servant()->paths()->format($path, 'server')),
			$this->root(),
			$pageOrder
		);

		return $this;
	}



	/**
	* Getters
	*/

	public function root () {
		return $this->getAndSet('root');
	}



	/**
	* Setters
	*/

	protected function setRoot () {
		return $this->set('root', $this->servant()->create()->category('root'));
	}



	/**
	* Private helpers
	*/

	/**
	* Find template files in file system
	*/
	private function findPageTemplates ($path = null) {
		$results = array();

		if ($path and is_dir($path)) {
			$formats = $this->servant()->constants()->formats('templates');

			// Files on this level
			$files = glob_files($path, $formats);
			foreach ($files as $file) {
				$results[pathinfo($file, PATHINFO_FILENAME)] = $this->servant()->paths()->format($file, false, 'server');
			}

			// Files in child directories
			foreach (glob_dir($path) as $dir) {
				$children = $this->findPageTemplates($dir, $formats);

				// Include non-empty sets of child pages
				if (!empty($children)) {
					if (count($children) < 2) {
						$keys = array_keys($children);
						$children = $children[$keys[0]];
					}
					$results[pathinfo($dir, PATHINFO_FILENAME)] = $children;
				}

			}
			unset($children);

			// Sort based on file or directory name, then lose the indexes
			uksort($results, 'strcasecmp');

		}

		return $results;
	}

	/**
	* Generate page/category nodes based on page files in a directory structure
	*
	* FLAG
	* * A little bloated
	*
	* @param array $pages A set of files available in the file system, as structured by $this->findPageTemplates()
	* @param ServantCategory $parent ServantCategory object to act as the parent of generated nodes, or null if not available
	* @param array $pageOrder Preferred order of the nodes to override the default order
	*
	* @return ServantCategory Parent node
	*
	*/
	private function generateNodes ($pages, $parentNode, $pageOrder = array()) {

		// Order of children
		$order = array();
		$pointer = $parentNode->stringPointer(true);
		if (array_key_exists($pointer, $pageOrder)) {
			$order = $pageOrder[$pointer];
		}



		// Reorder $pages according to our order map
		$orderedPages = array();

		// Pick values in order map first
		foreach ($order as $id) {
			if (isset($pages[$id])) {
				$orderedPages[$id] = $pages[$id];
			}
		}
		unset($id);

		// Add values that weren't already been picked
		foreach ($pages as $id => $value) {
			if (!isset($orderedPages[$id])) {
				$orderedPages[$id] = $value;
			}
		}
		unset($id, $value);



		// Generate page objects
		foreach ($orderedPages as $id => $value) {

			// Category
			if (is_array($value)) {
				$category = $this->servant()->create()->category($id, $parentNode);
				$this->generateNodes($value, $category, $pageOrder);

			// Page
			} else {
				$page = $this->servant()->create()->page($value, $parentNode, $id);
			}

		}

		return $parentNode;
	}

}

?>