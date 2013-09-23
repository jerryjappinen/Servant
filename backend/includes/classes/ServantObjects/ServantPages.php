<?php

class ServantPages extends ServantObject {

	/**
	* Properties
	*/
	protected $propertyCurrent 		= null;
	protected $propertyFiles 		= null;
	protected $propertyMap 			= null;
	protected $propertyPath 		= null;



	/**
	* Public getters
	*/

	// Return current page object
	public function current () {
		return $this->map($this->getAndSet('current'));
	}

	public function path ($format = null) {
		$path = $this->getAndSet('path');
		if ($format) {
			$path = $this->servant()->format()->path($path, $format);
		}
		return $path;
	}



	/**
	* Setters
	*/

	/**
	* Tree of selected page
	*/
	protected function setCurrent () {

		// Select the page most closely matching user input
		$tree = $this->selectPage($this->files(), $this->servant()->input()->page());

		return $this->set('current', $tree);
	}

	/**
	* All available template files that can be converted to pages, recursively and with paths
	*/
	protected function setFiles () {
		return $this->set('files', $this->findPageFiles($this->path('server'), $this->servant()->settings()->formats('templates')));
	}

	/**
	* All available pages as page objects
	*/
	public function setMap () {
		return $this->set('map', $this->generatePageObjects($this->files()));
	}

	/**
	* Path
	*/
	protected function setPath () {
		return $this->set('path', $this->servant()->paths()->pages('plain'));
	}



	/**
	* Private helpers
	**/



	/**
	* List available pages recursively
	*
	* FLAG
	*   - exclusion of settings file is a bit laborious
	*/
	private function findPageFiles ($path, $filetypes = array()) {
		$results = array();
		$blacklist = array();

		// Blacklist site settings file
		$blacklist[] = $this->path('plain').$this->servant()->settings()->packageContents('siteSettingsFile');

		// Files on this level
		foreach (glob_files($path, $filetypes) as $file) {

			// Check path against blacklisted values
			$value = $this->servant()->format()->path($file, 'plain', 'server');
			if (!in_array($value, $blacklist)) {
				$results[pathinfo($file, PATHINFO_FILENAME)] = $value;
			}

		}
		unset($value);

		// Non-empty child directories
		foreach (glob_dir($path) as $subdir) {
			$value = $this->findPageFiles($subdir, $filetypes);
			if (!empty($value)) {

				// Represent arrays with only one item as pages
				// NOTE the directory name is used as the key, not the filename
				if (count($value) < 2) {
					$keys = array_keys($value);
					$value = $value[$keys[0]];
				}

				$results[pathinfo($subdir, PATHINFO_FILENAME)] = $value;
			}
		}

		// Mix sort directories and files
		uksort($results, 'strcasecmp');

		return $results;
	}



	/**
	* Create page tree with actual objects
	*/
	private function generatePageObjects ($fileMap = array(), $parents = array()) {
		$result = $fileMap;

		foreach ($result as $id => $value) {
			$tree = $parents;
			$tree[] = $id;

			// Convert to page object
			if (is_string($value)) {
				$result[$id] = create_object(new ServantPage($this->servant()))->init($this, $tree);

			// Children are treated recursively
			} else if (is_array($value)) {
				$result[$id] = $this->generatePageObjects($value, $tree);
			}

		}

		return $result;
	}



	/**
	* Choose one page from those available, preferring the one detailed in $tree
	*/
	private function selectPage ($filesOnThisLevel, $tree, $level = 0) {
 
		// No preference or preferred item doesn't exist: auto select
		if (!isset($tree[$level]) or !array_key_exists($tree[$level], $filesOnThisLevel)) {

			// Cut out the rest of the preferred items
			$tree = array_slice($tree, 0, $level);

			// Auto select first item on this level
			$keys = array_keys($filesOnThisLevel);
			$tree[] = $keys[0];

		}

		// We need to go deeper
		if (is_array($filesOnThisLevel[$tree[$level]])) {
			return $this->selectPage($filesOnThisLevel[$tree[$level]], $tree, $level+1);

		// That was it
		} else {
			return array_slice($tree, 0, $level+1);
		}

	}

}

?>