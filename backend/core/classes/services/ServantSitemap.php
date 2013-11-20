<?php
/**
* Sitemap with a root category node
*
* FLAG
*   - This should not be a global service, actions should generate a sitemap if needed
*/
class ServantSitemap extends ServantObject {

	/**
	* Properties
	*/
	protected $propertyRoot = null;



	/**
	* Convenience
	*/
	public function dump ($parent = false) {

		// Dump from root if not specified
		if (!$parent) {
			$parent = $this->root()->children();
		}

		$output = array();
		foreach ($parent as $node) {
			$output[$node->id()] = $node->children() ? $this->dump($node->children()) : $node->name();
		}

		return $output;
	}

	public function pages () {
		$arguments = func_get_args();
		return $this->root()->children($arguments);
	}



	/**
	* Initialization
	*/
	public function initialize () {
		$this->generateNodes($this->findPageTemplates($this->servant()->paths()->pages('server')), $this->root());
		return $this;
	}

	/**
	* Root page node
	*/
	protected function setRoot () {
		return $this->set('root', $this->servant()->create()->category('root'));
	}



	/**
	* Helpers
	*/
	public function select ($tree = null) {
		$tree = func_get_args();
		return $this->selectNode(array_flatten($tree), $this->root());
	}

	/**
	* Choose one page node from all available nodes, preferring the one pinpointed to in $tree by ID
	*
	* FLAG
	*   - This works with sitemap-generated robust sitemap, but not very dynamically with arbitrary node maps
	*/
	public function selectNode ($tree, $parent) {
		$tree = to_array($tree);

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

		// Fallback to go deeper
		if ($result->category()) {
			$result = $this->selectNode('', $result->children(0));
		}

		return $result;
	}



	/**
	* Private helpers
	*/

	/**
	* Find template files in file system
	*/
	public function findPageTemplates ($path) {
		$formats = $this->servant()->settings()->formats('templates');
		$results = array();

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

		return $results;
	}

	public function generateNodes ($pages, $parent = null) {

		foreach ($pages as $key => $value) {

			// Category
			if (is_array($value)) {
				$category = $this->servant()->create()->category($key, $parent);
				$this->generateNodes($value, $category);

			// Page
			} else {
				$page = $this->servant()->create()->page($value, $parent);
			}

		}

		return $parent;
	}

}

?>