<?php

/**
* Sitemap service
*
* FLAG
*   - Maybe this should be available under ServantSite
*/
class ServantPages extends ServantObject {

	/**
	* Properties
	*/
	protected $propertyMap = null;



	/**
	* Convenience
	*/

	// FLAG weird naming
	public function level ($tree = array()) {
		$pages = array();

		// Allow tree traversal
		$tree = func_get_args();

		// Pick pages on this level
		foreach ($this->map(array_flatten($tree)) as $id => $value) {

			// Normalize pages with children
			if (is_array($value)) {
				$subPages = $this->level($tree, $id);
				$subPagesKeys = array_keys($subPages);
				$value = $subPages[$subPagesKeys[0]];
			}

			$pages[] = $value;
		}

		return $pages;
	}



	/**
	* Public getters
	*/

	public function path ($format = null) {
		return $this->servant()->paths()->pages($format);
	}



	/**
	* Setters
	*/

	/**
	* All available pages as page objects
	*
	* FLAG
	*   - Should be named "All"
	*   - I should create and init page only when they're actually called
	*/
	protected function setMap () {
		$templateFiles = $this->findPageFiles($this->path('server'), $this->servant()->settings()->formats('templates'));

		// Warn if there are no pages
		if (!$templateFiles) {
			$this->fail('No pages available');
		}

		return $this->set('map', $this->generatePageObjects($templateFiles));
	}



	/**
	* Private helpers
	**/



	/**
	* List available pages recursively
	*/
	private function findPageFiles ($path, $filetypes = array()) {
		$results = array();

		// Files on this level
		foreach (glob_files($path, $filetypes) as $file) {
			$results[pathinfo($file, PATHINFO_FILENAME)] = $this->servant()->format()->path($file, 'plain', 'server');
		}

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

		// Iterate through existing filemap
		foreach ($result as $id => $value) {
			$tree = $parents;
			$tree[] = $id;

			// Convert to page object
			if (is_string($value)) {
				$result[$id] = create_object(new ServantPage($this->servant()))->init($tree, $value);

			// Children are treated recursively
			} else if (is_array($value)) {
				$result[$id] = $this->generatePageObjects($value, $tree);
			}

		}

		return $result;
	}

}

?>