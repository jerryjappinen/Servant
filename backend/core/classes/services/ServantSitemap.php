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

		$path = $this->servant()->paths()->pages('server');
		$files = $this->findPageTemplates($path);

		// Root ges some children
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
				$results[pathinfo($dir, PATHINFO_FILENAME)] = count($children) > 1 ? $children : $children[0];
			}

		}
		unset($children);

		// Sort based on file or directory name, then lose the indexes
		uksort($results, 'strcasecmp');
		return array_values($results);
	}

	/**
	* Create pseudo pages that can be easily converted into real pages
	*/
	private function treatFileMap ($array) {
		$result = array();

		foreach ($array as $value) {

			// Generate page
			if (is_string($value)) {
				$path = $value;
				$children = array();

				$result[] = array('path' => $value, 'children' => array());

			// Generate master page with children
			} else if (is_array($value)) {

				// Normalize first child
				$children = $this->treatFileMap($value);
				$firstChild = array_shift($children);
				$path = $firstChild['path'];

				$result[] = array('path' => $firstChild['path'], 'children' => $children);

			}

		}

		return $result;
	}

	/**
	* Convert pseudo pages into real page objects
	*/
	private function generatePages ($array, $parent) {
		foreach ($array as $pageInfo) {
			$page = $this->generate('pageNode', $pageInfo['path'], $parent);
			$this->generatePages($pageInfo['children'], $page);
		}
		return $parent;
	}

}

?>