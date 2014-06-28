<?php

/**
* A page
*
* NOTE
*   - User does not usually know if she has a category or page node
*
* DEPENDENCIES
*   ???
*/
class ServantPage extends ServantNode {

	/**
	* Properties
	*/
	protected $propertyEndpoint 	= null;
	protected $propertyOutput 		= null;
	protected $propertyPath 		= null;
	protected $propertyScripts 		= null;
	protected $propertyStylesheets 	= null;
	protected $propertyType 		= null;



	/**
	* Convenience API for pages
	*/

	public function category () {
		return $this->parent();
	}

	public function isCategory () {
		return false;
	}

	public function isHome () {
		$home = false;

		// If this page is first of its siblings
		if ($this->index() === 0) {

			$home = true;
			foreach ($this->parents() as $parent) {
				if ($parent->index() > 0) {
					$home = false;
					break;
				}
			}

		}

		return $home;
	}

	public function isPage () {
		return true;
	}

	public function page () {
		return $this;
	}



	/**
	* Category-like behavior
	*/

	public function children () {
		return func_num_args() ? null : array();
	}



	/**
	* Template path is needed upon initialization
	*/
	protected function initialize ($path, $parent, $id = null) {
		$this->setParent($parent)->setPath($path);

		// Custom ID
		if ($id) {
			$this->setId($id);
		}

		return $this;
	}



	/**
	* Getters
	*/

	// User-facing URL
	public function endpoint ($format = false) {
		$path = $this->getAndSet('endpoint');
		if ($format) {
			$path = $this->servant()->paths()->format($path, $format);
		}
		return $path;
	}

	public function output () {
		$output = $this->get('output');

		// Unset
		if ($output === null) {
			$arguments = func_get_args();
			call_user_func_array(array($this, 'setOutput'), $arguments);
			$output = $this->get('output');
		}

		return $output;
	}

	// Template file
	public function path ($format = false) {
		$path = $this->getAndSet('path');
		if ($format) {
			$path = $this->servant()->paths()->format($path, $format);
		}
		return $path;
	}

	// Script files
	public function scripts ($format = false) {
		$files = $this->getAndSet('scripts');
		if ($format) {
			foreach ($files as $key => $filepath) {
				$files[$key] = $this->servant()->paths()->format($filepath, $format);
			}
		}
		return $files;
	}

	// Stylesheet files
	public function stylesheets ($format = false) {
		$files = $this->getAndSet('stylesheets');
		if ($format) {
			foreach ($files as $key => $filepath) {
				$files[$key] = $this->servant()->paths()->format($filepath, $format);
			}
		}
		return $files;
	}

	// Template format
	public function type () {
		return $this->getAndSet('type');
	}



	/**
	* Setters
	*/

	// Path to this page in the site action
	protected function setEndpoint () {
		$action = $this->servant()->constants()->actions('site');
		return $this->set('endpoint', $this->servant()->paths()->endpoint($action, 'plain', $this->pointer()));
	}

	protected function setId ($input = null) {

		// Allow overriding auto set
		if (is_string($input)) {
			$input = trim_whitespace($input);
			if (!empty($input)) {
				$id = $input;
			}
		}

		// Default
		if (!isset($id)) {
			$id = pathinfo($this->path(), PATHINFO_FILENAME);
		}

		return $this->set('id', $id);
	}

	// Template content as a string
	protected function setOutput () {


		// Allow passing input to page when generating page
		$arguments = func_get_args();

		// Variables passed to page scripts
		$scriptVariables = array(
			'servant' => $this->servant(),
			'parameters' => array_flatten($arguments),
			'page' => $this,
		);

		// Read content from source file
		$fileContent = $this->servant()->files()->read($this->path('server'), $scriptVariables);

		// Save file content
		return $this->set('output', $fileContent);
	}

	// Path to the template file
	protected function setPath ($path) {

		// Template file must exist
		if (!is_file($this->servant()->paths()->format($path, 'server'))) {
			$this->fail('Non-existing template file given to page ("'.$path.'").');
		}

		return $this->set('path', $path);
	}

	// Paths to script files under pages, relevant to this page
	protected function setScripts () {
		return $this->set('scripts', $this->filterPageFiles('scripts'));
	}

	// Paths to stylesheet files under pages, relevant to this page
	protected function setStylesheets () {
		return $this->set('stylesheets', $this->filterPageFiles('stylesheets'));
	}

	// Template file type
	protected function setType () {
		return $this->set('type', pathinfo($this->path(), PATHINFO_EXTENSION));
	}



	/**
	* Private helpers
	*/

	/**
	* Select the files under pages that are relevant for this page (i.e. stylesheets or scripts in parent folders)
	*/
	private function filterPageFiles ($formatType) {

		// Origin directories
		$pagesDir = $this->servant()->paths()->pages('server');
		$dirs = array_filter(explode('/', unprefix(dirname($this->path('server')).'/', $pagesDir)));
		array_unshift($dirs, '');

		// Compose paths for valid parent directories
		for ($i = 1; $i < count($dirs); $i++) { 
			$dirs[$i] = $dirs[$i-1].$dirs[$i].'/';
		}
		unset($i);

		// List files in the directories
		$files = array();
		foreach ($dirs as $dir) {
			$dir = $pagesDir.$dir;
			foreach (glob_files($dir, $this->servant()->constants()->formats($formatType)) as $file) {
				$files[] = $this->servant()->paths()->format($file, false, 'server');
			}
		}

		return $files;
	}

}

?>