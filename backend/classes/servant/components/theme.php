<?php

// FLAG replication with scripts & stylesheets
class ServantTheme extends ServantObject {

	// Properties
	protected $propertyId 				= null;
	protected $propertyPath 			= null;
	protected $propertyScripts 			= null;
	protected $propertyStylesheets 		= null;



	// Select ID when initializing
	protected function initialize ($id = false) {
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
	// FLAG I don't want to double get everything, but don't want to get everything either - what's the best way to do this?
	protected function setId ($id = null) {

		// Silent fallback
		if (!$this->servant()->available()->theme($id)) {

			// Site's own theme
			if ($this->servant()->available()->theme($this->servant()->site()->id())) {
				$id = $this->servant()->site()->id();

			// Template's theme
			} else if ($this->servant()->available()->theme($this->servant()->template()->id())) {
				$id = $this->servant()->template()->id();

			// Global default
			} else if ($this->servant()->available()->theme($this->servant()->settings()->defaults('theme'))) {
				$id = $this->servant()->settings()->defaults('theme');

			// Whatever's available
			} else {
				$id = $this->servant()->available()->themes(0);

				if ($id === null) {
					$this->fail('No themes available');
				}
			}
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