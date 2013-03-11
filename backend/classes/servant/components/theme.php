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



	// Public getters
	public function id () {
		return $this->getAndSet('id');
	}

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

	protected function setPath () {
		return $this->set('path', $this->servant()->paths()->themes('plain').$this->id().'/');
	}

	protected function setStylesheets () {
		$files = array();
		$dir = $this->path('server');
		if (is_dir($dir)) {
			foreach (rglob_files($dir, $this->servant()->settings()->formats('stylesheets')) as $key => $path) {
				$files[$key] = $this->servant()->format()->path($path, false, 'server');
			}
		}
		return $this->set('stylesheets', $files);
	}

	protected function setScripts () {
		$files = array();
		$dir = $this->path('server');
		if (is_dir($dir)) {
			foreach (rglob_files($dir, $this->servant()->settings()->formats('scripts')) as $key => $path) {
				$files[$key] = $this->servant()->format()->path($path, false, 'server');
			}
		}
		return $this->set('scripts', $files);
	}

}

?>