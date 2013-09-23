<?php

class ServantSite extends ServantObject {

	/**
	* Properties
	*/
	protected $propertyIcon 		= null;
	protected $propertyLanguage 	= null;
	protected $propertyName 		= null;
	protected $propertyPage 		= null;
	protected $propertyPages 		= null;
	protected $propertyPath 		= null;
	protected $propertyScripts 		= null;
	protected $propertySettings		= null;
	protected $propertyStylesheets 	= null;



	/**
	* Public getters
	*/

	public function icon ($format = null) {
		$icon = $this->getAndSet('icon');
		if ($format and !empty($icon)) {
			$icon = $this->servant()->format()->path($icon, $format);
		}
		return $icon;
	}

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



	/**
	* Setters
	*/

	/**
	* Path to site icon comes from settings or remains an empty string
	*/
	protected function setIcon () {
		$result = '';
		$setting = $this->settings('icon');
		if (!empty($setting) and in_array(pathinfo($setting, PATHINFO_EXTENSION), $this->servant()->settings()->formats('iconImages')) and is_file($this->path('server').$setting)) {
			$result = $this->path('plain').$setting;
		}
		return $this->set('icon', $result);
	}

	/**
	* Language
	*
	* FLAG
	*   - This should be a list of supported languages in order of preference
	*   - Hardcoded default
	*/
	protected function setLanguage () {

		// Hardcoded fallback -_-
		$default = 'en';
		$language = '';

		// Language from site settings
		if ($this->settings('language')) {
			$language = $this->settings('language');

		// Global default
		} else if ($this->servant()->settings()->defaults('language')) {
			$language = $this->servant()->settings()->defaults('language');
		}

		// Validate language string
		if (!is_string($language) or mb_strlen($language) != 2) {
			$language = $default;
		}

		return $this->set('language', $language);
	}

	/**
	* Name comes from settings or is created from ID
	*
	* FLAG
	*   - Hardcoded default name
	*/
	protected function setName () {
		$name = $this->settings('name');
		return $this->set('name', $name ? $name : 'Home');
	}

	/**
	* Current page as child object
	*/
	protected function setPage () {

		// Select page based on input
		$selectedPage = $this->servant()->input()->page();

		return $this->set('page', create_object(new ServantPage($this->servant()))->init($this, $selectedPage));
	}

	/**
	* Pages of this site
	*/
	protected function setPages () {
		return $this->set('pages', $this->findPages($this->path('server'), $this->servant()->settings()->formats('templates')));
	}

	/**
	* Path
	*/
	protected function setPath () {
		return $this->set('path', $this->servant()->paths()->pages('plain'));
	}

	/**
	* Site's settings
	*/
	protected function setSettings () {

		// Basic format of site settings
		$settings = array(
			'icon' => '',
			'language' => '',
			'name' => '',
			'pageNames' => array(),
		);

		// Look for settings file
		$path = $this->path('server').$this->servant()->settings()->packageContents('siteSettingsFile');
		if (is_file($path)) {

			// Read settings file, turn into an array
			$temp = json_decode(suffix(prefix(trim(file_get_contents($path)), '{'), '}'), true);
			if (is_array($temp)) {
				foreach ($settings as $key => $default) {

					// Only accept non-empty values
					if (array_key_exists($key, $temp) and !empty($temp[$key])) {

						// Numerical entries can be turned into strings by us
						if (is_string($default) and (is_string($temp[$key])) or is_numeric($temp[$key])) {
							$settings[$key] = trim_text(strval($temp[$key]), true);

						// Otherwise type must match
						} else if (gettype($default) === gettype($temp[$key])) {
							$settings[$key] = $temp[$key];
						}

					}
				}
			}

		}

		// Normalize name conversions array
		if (!empty($settings['pageNames'])) {
			$temp = array();
			foreach (array_flatten($settings['pageNames'], false, true) as $key => $value) {
				$temp[mb_strtolower($key)] = $value;
			}
			$settings['pageNames'] = $temp;
		}

		return $this->set('settings', $settings);
	}

	/**
	* Stylesheet files
	*/
	protected function setStylesheets () {
		return $this->set('stylesheets', $this->findFiles($this->servant()->settings()->formats('stylesheets')));
	}

	/**
	* Script files
	*/
	protected function setScripts () {
		return $this->set('scripts', $this->findFiles($this->servant()->settings()->formats('scripts')));
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
	private function findPages ($path, $filetypes = array()) {
		$results = array();
		$blacklist = array();

		// Blacklist site settings file
		$blacklist[] = $this->path('plain').$this->servant()->settings()->packageContents('siteSettingsFile');

		// Blacklist site icon
		$iconPath = $this->settings('icon');
		if (!empty($iconPath)) {
			$blacklist[] = $this->path('plain').$iconPath;
		}
		unset($iconPath);

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

	/**
	* Helper to find any files, returns them uncategorized
	*/
	private function findFiles ($formats = array()) {
		$files = array();
		$path = $this->path('server');

		// Individual file
		if (is_file($path)) {
			$files[] = $this->path('plain');

		// All files in directory
		} else if (is_dir($path)) {
			foreach (rglob_files($path, $formats) as $file) {
				$files[] = $this->servant()->format()->path($file, false, 'server');
			}
		}

		return $files;
	}

}

?>