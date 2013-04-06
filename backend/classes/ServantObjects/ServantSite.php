<?php

class ServantSite extends ServantObject {

	// Properties
	protected $propertyArticle 			= null;
	protected $propertyArticles 		= null;
	protected $propertyConfiguration	= null;
	protected $propertyId 				= null;
	protected $propertyName 			= null;
	protected $propertyPath 			= null;
	protected $propertyStylesheets 		= null;
	protected $propertyScripts 			= null;



	// Public getters

	public function path ($format = null) {
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

	// Selected article as child object
	protected function setArticle () {

		// Select article based on input
		$selectedArticle = $this->servant()->input()->article();

		return $this->set('article', create(new ServantArticle($this->servant()))->init($this, $selectedArticle));
	}

	protected function setArticles () {
		return $this->set('articles', $this->findArticles($this->path('server'), $this->servant()->settings()->formats('templates')));
	}

	protected function setConfiguration () {

		// Base format
		$configuration = array('template' => '', 'theme' => '');

		// Look for configuration files
		$path = $this->path('server').$this->servant()->settings()->packageContents('siteConfigurationFile');
		if (is_file($path)) {

			// Read configuration, interpret into an array
			$contents = str_replace(array(',', ';', "\n\n"), "\n", trim_text(file_get_contents($path)));
			$contents = explode("\n", $contents);
			$keys = array_keys($configuration);
			$keyCount = count($keys);

			// Evaluate any key-value pairs found
			$i = 0;
			$j = 0;
			foreach ($contents as $key => $value) {

				// Only accept uncommented lines that have a ":"
				if (!in_array(mb_substr($value, 0, 1), array('#', '/')) and strpos($value, ':') !== false) {
					$value = explode(':', $value);

					// Sanitize content
					$key = trim($value[0]);
					$value = trim($value[1]);
					if (in_array($key, $keys) and !empty($value)) {
						$configuration[$key] = ''.$value;

						// Break when we've found everything
						$j++;
						if ($j >= $keyCount) {
							break;
						}

					}

				}

				// Respect maximum iteration count to prevent abuse
				if ($i > 9) {
					break;
				} else {
					$i++;
				}

			}

		}

		return $this->set('configuration', $configuration);
	}

	protected function setId () {

		// Try using input
		$id = $this->servant()->input()->site();

		// Given ID is invalid
		if (!$id or !$this->servant()->available()->site($id)) {

			// Other options
			$default = $this->servant()->settings()->defaults('site');
			$first = $this->servant()->available()->sites(0);

			// Global default
			if ($this->servant()->available()->site($default)) {
				$id = $default;

			// Whatever's available
			} else if (isset($first)) {
				$id = $first;

			// No sites
			} else {
				$this->fail('No sites available');
			}

		}

		return $this->set('id', $id);
	}

	protected function setName () {
		return $this->set('name', $this->servant()->format()->name($this->id()));
	}

	protected function setPath () {
		return $this->set('path', $this->servant()->paths()->sites('plain').$this->id().'/');
	}

	// Stylesheet files
	protected function setStylesheets () {
		return $this->set('stylesheets', $this->findFiles('stylesheets'));
	}

	// Script files
	protected function setScripts () {
		return $this->set('scripts', $this->findFiles('scripts'));
	}



	// Private helpers

	// List available articles recursively
	// FLAG excluding configuration file is a bit laborious
	private function findArticles ($path, $filetypes = array()) {
		$results = array();

		// Unaccetable files
		$blacklist = array($this->path('plain').$this->servant()->settings()->packageContents('siteConfigurationFile'));

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
			$value = $this->findArticles($subdir, $filetypes);
			if (!empty($value)) {

				// Represent arrays with only one item as articles
				// NOTE the directory name is used as the key, not the filename
				if (count($value) < 2) {
					$keys = array_keys($value);
					$value = $value[$keys[0]];
				}

				$results[pathinfo($subdir, PATHINFO_FILENAME)] = $value;
			}
		}

		// Mix sort directories and files
		ksort($results);

		return $results;
	}

	// Helper to find any files, returns them uncategorized
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