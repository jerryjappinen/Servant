<?php

class ServantSitemap extends ServantObject {

	/**
	* Properties
	*/
	protected $propertyRoot 	= null;



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
	* Private helpers
	**/

	/**
	* Find template files in file system
	*/
	public function findPageTemplates ($path) {
		$formats = $this->servant()->settings()->formats('templates');
		$results = array();

		// Files on this level
		$files = glob_files($path, $formats);
		foreach ($files as $file) {
			$results[pathinfo($file, PATHINFO_FILENAME)] = $this->servant()->format()->path($file, false, 'server');
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