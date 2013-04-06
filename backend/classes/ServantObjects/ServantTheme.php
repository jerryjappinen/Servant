<?php

class ServantTheme extends ServantObject {

	// Properties
	protected $propertyId 				= null;
	protected $propertyPath 			= null;
	protected $propertyScripts 			= null;
	protected $propertyStylesheets 		= null;



	// Select ID when initializing
	public function initialize ($id = false) {
		if ($id) {
			$this->setId($id);
		}
		return $this;
	}



	// Public getters for paths

	public function path ($format = false) {
		$path = $this->getAndSet('path');
		if ($format) {
			$path = $this->servant()->format()->path($path, $format);
		}
		return $path;
	}

	public function scripts ($format = false) {
		$files = $this->getAndSet('scripts');
		if ($format) {
			foreach ($files as $key => $filepath) {
				$files[$key] = $this->servant()->format()->path($filepath, $format);
			}
		}
		return $files;
	}

	public function stylesheets ($format = false) {
		$files = $this->getAndSet('stylesheets');
		if ($format) {
			foreach ($files as $key => $filepath) {
				$files[$key] = $this->servant()->format()->path($filepath, $format);
			}
		}
		return $files;
	}



	// Setters

	// Theme identity
	protected function setId ($input = null) {

		// List our options, in order of preference
		$preferredIds = array(

			// Whatever we got as input parameter here
			$input,

			// Theme defined in site settings
			$this->servant()->site()->settings('theme'),

			// Theme with the same name as site
			$this->servant()->site()->id(),

			// Theme with the same name as template
			$this->servant()->template()->id(),

			// Default from global settings
			$this->servant()->settings()->defaults('theme'),

			// Whatever's available
			$id = $this->servant()->available()->themes(0)

		);

		// Go through our options, try to find a template
		foreach ($preferredIds as $id) {
			if ($this->servant()->available()->template($id)) {
				break;
			}
		}

		// Require a valid template
		// FLAG I want Servant to work without a theme
		if ($id === null) {
			$this->fail('No themes available');
		}

		return $this->set('id', $id);
	}



	// Theme is either a file or a folder within the themes directory
	protected function setPath () {
		$path = '';
		$serverPath = $this->servant()->paths()->themes('server').$this->id();

		// Search for a directory
		if (is_dir($serverPath.'/')) {
			$path = '/';

		// Search for one file
		} else {

			// Go through acceptable types, break when we find a match
			$formats = array_merge($this->servant()->settings()->formats('stylesheets'), $this->servant()->settings()->formats('scripts'));
			foreach ($formats as $format) {
				if (is_file($serverPath.'.'.$format)) {
					$path = '.'.$format;
					break;
				}
			}

		}

		// Make sure we found a proper path
		if (!empty($path)) {
			$path = $this->servant()->paths()->themes('plain').$this->id().$path;
		}

		return $this->set('path', $path);
	}



	// Files

	// Stylesheet files
	protected function setStylesheets () {
		return $this->set('stylesheets', $this->findFiles('stylesheets'));
	}

	// Script files
	protected function setScripts () {
		return $this->set('scripts', $this->findFiles('scripts'));
	}

	// Helper to find files
	private function findFiles ($formatsType) {
		$files = array();
		$path = $this->path('server');

		// Individual file
		if (is_file($path)) {
			$files[] = $this->path('plain');

		// All template files in directory
		} else if (is_dir($path)) {
			foreach (rglob_files($path, $this->servant()->settings()->formats($formatsType)) as $file) {
				$files[] = $this->servant()->format()->path($file, false, 'server');
			}
		}

		return $files;
	}

}

?>