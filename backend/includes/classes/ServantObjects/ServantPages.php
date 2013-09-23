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
	* Current page
	*/
	protected function setCurrent () {

		// Select page based on input
		$selectedPage = $this->servant()->input()->page();

		return $this->set('page', create_object(new ServantPage($this->servant()))->init($this, $selectedPage));
	}

	/**
	* All pages, recursively in a map, with file paths
	*/
	protected function setFiles () {
		return $this->set('files', $this->findPages($this->path('server'), $this->servant()->settings()->formats('templates')));
	}

	/**
	* Full map with page objects
	*/
	public function setMap () {
		return $this->set('map', $this->generatePathObjects($this->files()));
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
	* Create page tree with actual objects
	*
	* FLAG
	*   - is this an overkill?
	*/
	private function generatePathObjects ($fileMap = array(), $parents = array()) {
		$result = $fileMap;

		foreach ($result as $id => $value) {
			$tree = $parents;
			$tree[] = $id;

			// Convert to page object
			if (is_string($value)) {
				// $result[$id] = implode(' > ', $tree);
				$result[$id] = create_object(new ServantPage($this->servant()))->init($this->servant()->site(), $tree);

			// Children are treated recursively
			} else if (is_array($value)) {
				$result[$id] = $this->generatePathObjects($value, $tree);
			}

		}

		return $result;
	}

	/**
	* List available pages recursively
	*
	* FLAG
	*   - exclusion of settings file is a bit laborious
	*/
	private function findPages ($path, $filetypes = array()) {
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
			$value = $this->findPages($subdir, $filetypes);
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

}

?>