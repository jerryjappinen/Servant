<?php

class ServantSitemap extends ServantObject {

	/**
	* Properties
	*/
	protected $propertyRoot 	= null;



	/**
	* Initialization
	*/
	public function initialize () {

		// Find files
		$path = $this->servant()->paths()->pages('server');
		$this->generatePagesForCategory($this->findPageTemplates($path), $this->root());

		return $this;
	}

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
	* Root page node
	*/
	protected function setRoot () {
		return $this->set('root', $this->generate('categoryNode', 'root'));
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

	public function generatePagesForCategory ($pages, $parent = null) {

		foreach ($pages as $key => $value) {

			// Category
			if (is_array($value)) {
				$category = $this->generate('categoryNode', $key, $parent);
				$this->generatePagesForCategory($value, $category);

			// Page
			} else {
				$page = $this->generate('pageNode', $value, $parent);
			}

		}

		return $parent;
	}



















	/**
	* Create pseudo pages that can be easily converted into real pages
	*/
	public function treatFileMap ($array, $parentCategoryId = false) {
		$result = array();

		$i = 0;
		foreach ($array as $id => $value) {

			$page = array(
				'path' => '',
				'id' => '',
				'categoryId' => $id,
				'children' => array(),
			);

			// Normalize arrays with only one item
			if (is_array($value) and count($value) < 2) {
				$keys = array_keys($value);
				$page['path'] = $value[$keys[0]];
				$page['id'] = $id;

			// Generate page
			} else if (is_string($value)) {
				$page['path'] = $value;
				$page['id'] = $id;

			// Generate master page with children
			} else if (is_array($value)) {

				// Normalize first child
				$children = $this->treatFileMap($value, $id);
				$firstChild = array_shift($children);
				array_unshift($children, $firstChild);

				// FLAG firstChild children?!?!?!?!?!
				// Make new category for first child children
				// array_unshift($children, $this->treatFileMap($firstChild['children'], $firstChild['categoryId']));

				// Add parent page and it's children to restuls
				$page = array(
					'path' => $firstChild['path'],
					'id' => $firstChild['id'],
					'categoryId' => $firstChild['categoryId'],
					'children' => $children,
				);

			}

			// Use parent's category ID
			if ($parentCategoryId and $i === 0) {
				$page['categoryId'] = $parentCategoryId;
			}

			// Add to results
			if ($page['path']) {
				$result[] = $page;
			}

			$i++;
		}

		return $result;
	}

	/**
	* Convert pseudo pages into real page objects
	*/
	public function generatePages_foo ($array, $parent) {
		foreach ($array as $pageInfo) {
			$page = $this->generate('pageNode', $pageInfo['path'], $parent);
			foreach (array('id', 'categoryId') as $key) {
				if ($pageInfo[$key]) {
					$page->$key($pageInfo[$key]);
				}
			}
			$this->generatePages($pageInfo['children'], $page);
		}
		return $parent;
	}

}

?>