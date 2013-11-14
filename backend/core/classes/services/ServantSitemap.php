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
		$files = $this->findPageTemplates($path);

		// Add all available pages as children of root
		$this->generatePages($this->treatFileMap($files), $this->root());

		return $this;
	}

	/**
	* Root page node
	*/
	protected function setRoot () {
		return $this->set('root', $this->generate('rootPageNode'));
	}



	/**
	* Private helpers
	**/

	/**
	* Find template files in file system
	*/
	private function findPageTemplates ($path) {
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
				$results[pathinfo($dir, PATHINFO_FILENAME)] = $children;
			}

		}
		unset($children);



		// Sort based on file or directory name, then lose the indexes
		uksort($results, 'strcasecmp');
		return $results;
	}

	/**
	* Create pseudo pages that can be easily converted into real pages
	*/
	private function treatFileMap ($array, $parentCategoryId = false) {
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
				$page['id'] = $keys[0];

			// Generate page
			} else if (is_string($value)) {
				$page['path'] =$value;
				$page['id'] = $id;

			// Generate master page with children
			} else if (is_array($value)) {

				// Normalize first child
				$children = $this->treatFileMap($value, $id);
				$firstChild = array_shift($children);
				$path = $firstChild['path'];

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
	private function generatePages ($array, $parent) {
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