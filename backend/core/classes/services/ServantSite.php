<?php

/**
* A web site, available as service
*
* FLAG
*   - Remove direct dependency between user-facing setting names and class properties
* 
*/
class ServantSite extends ServantObject {

	/**
	* Properties
	*/
	protected $propertyBrowserCache 		= null;
	protected $propertyDescription 			= null;
	protected $propertyExternalScripts 		= null;
	protected $propertyExternalStylesheets 	= null;
	protected $propertyIcon 				= null;
	protected $propertyLanguage 			= null;
	protected $propertyName 				= null;
	protected $propertyPageNames 			= null;
	protected $propertyPageOrder 			= null;
	protected $propertyPageTemplates 		= null;
	protected $propertyScripts 				= null;
	protected $propertyServerCache 			= null;
	protected $propertySplashImage 			= null;
	protected $propertyStylesheets 			= null;
	protected $propertyTemplate 			= null;



	/**
	* Take original settings in during initialization (all are optional)
	*/
	public function initialize () {

		// Read settings from JSON
		$manifest = $this->readJsonFile($this->servant()->paths()->manifest('server'));
		if ($manifest) {

			// This is what we can set
			$properties = array(
				'assets',
				'browserCache',
				'description',
				'icon',
				'language',
				'name',
				'pageNames',
				'pageOrder',
				'pageTemplates',
				'serverCache',
				'splashImage',
				'template',
			);

			// Run setters if values are given
			foreach ($properties as $key) {
				$parameters = array();
				if (isset($manifest[$key])) {
					$parameters[] = $manifest[$key];
					$this->callSetter($key, $parameters);
				}
			}

		}

		return $this;
	}



	/**
	* Getters
	*/

	/**
	* External scripts
	*/
	protected function externalScripts () {
		$arguments = func_get_args();
		return $this->getAndSet('externalScripts', $arguments);
	}

	/**
	* External stylesheets
	*/
	protected function externalStylesheets () {
		$arguments = func_get_args();
		return $this->getAndSet('externalStylesheets', $arguments);
	}

	public function icon ($format = null) {
		$path = $this->getAndSet('icon');
		if ($format and !empty($path)) {
			$path = $this->servant()->paths()->format($path, $format);
		}
		return $path;
	}

	public function scripts ($format = false) {
		$files = $this->getAndSet('scripts');
		if ($format) {
			foreach ($files as $key => $filepath) {
				$files[$key] = $this->servant()->paths()->format($filepath, $format);
			}
		}
		return $files;
	}

	public function splashImage ($format = null) {
		$path = $this->getAndSet('splashImage');
		if ($format and !empty($path)) {
			$path = $this->servant()->paths()->format($path, $format);
		}
		return $path;
	}

	public function stylesheets ($format = false) {
		$files = $this->getAndSet('stylesheets');
		if ($format) {
			foreach ($files as $key => $filepath) {
				$files[$key] = $this->servant()->paths()->format($filepath, $format);
			}
		}
		return $files;
	}



	/**
	* Manifest setters
	*
	* These call class property setters. Manifest can include anything that we map to class properties with these.
	*/

	// protected function setManifestAssets
	protected function setAssets ($input = null) {
		$scripts = array();
		$stylesheets = array();

		$formats = $this->servant()->constants()->formats();
		$scriptFormats = $formats['scripts']['js'];
		$stylesheetFormats = $formats['stylesheets']['css'];

		// Pick and sort URLs based on file extension
		$input = array_flatten(to_array($input));
		if (!empty($input)) {
			foreach ($input as $url) {

				$url = trim($url);
				$extension = pathinfo($url, PATHINFO_EXTENSION);

				// JavaScript
				if (in_array($extension, $scriptFormats)) {
					$scripts[] = $url;

				// Stylesheet
				} else if (in_array($extension, $stylesheetFormats)) {
					$stylesheets[] = $url;
				}

			}
		}

		// Call property setters
		if (!empty($scripts)) {
			call_user_func(array($this, 'setExternalScripts'), $scripts);
		}
		if (!empty($stylesheets)) {
			call_user_func(array($this, 'setExternalStylesheets'), $stylesheets);
		}

		return $this;
	}



	/**
	* Setters
	*/

	/**
	* Browser cache time (used in cache headers)
	*/
	protected function setBrowserCache ($input = null) {
		return $this->set('browserCache', $this->resolveCacheTime($input, $this->servant()->constants()->defaults('browserCache')));
	}

	/**
	* Description string
	*/
	protected function setDescription ($input = null) {
		$result = '';

		// A string will do
		if ($input and is_string($input)) {
			$result = $input;
		}

		return $this->set('description', trim_text($result));
	}

	/**
	* External scripts
	*/
	protected function setExternalScripts () {
		$result = array();
		$arguments = func_get_args();
		if (!empty($arguments)) {
			$result = array_flatten(to_array($arguments));
		}
		return $this->set('externalScripts', $result);
	}

	/**
	* External stylesheets
	*/
	protected function setExternalStylesheets () {
		$result = array();
		$arguments = func_get_args();
		if (!empty($arguments)) {
			$result = array_flatten(to_array($arguments));
		}
		return $this->set('externalStylesheets', $result);
	}

	/**
	* Path to site icon comes from settings or remains an empty string
	*/
	protected function setIcon ($input = null) {
		return $this->set('icon', $this->resolveImageFile($input));
	}

	/**
	* Language
	*
	* FLAG
	*   - Hardcoded default
	*   - Should this be a list of supported languages in order of preference?
	*/
	protected function setLanguage ($input = null) {
		$result = 'en';

		// Language from site settings
		if ($input and is_string($input)) {
			$result = $input;

		// Global default
		} else {
			$globalDefault = $this->servant()->constants()->defaults('language');
			if ($globalDefault and is_string($globalDefault)) {
				$result = $globalDefault;
			}
		}

		return $this->set('language', trim_text($result, true));
	}

	/**
	* Name comes from settings or is created from ID
	*
	* FLAG
	*   - Attempt to generate name from path
	*/
	protected function setName ($input = null) {
		$result = '';

		// A string will do
		if ($input and is_string($input)) {
			$result = $input;
		} else {
			$result = $this->servant()->constants()->defaults('siteName');
		}

		return $this->set('name', trim_text($result, true));
	}

	/**
	* Overrides for page naming
	*/
	protected function setPageNames ($input = null) {
		$names = array();

		// A flat array will do
		if ($input and is_array($input)) {
			$names = $this->normalizePageTreeHash($input);
		}

		return $this->set('pageNames', $names);
	}

	/**
	* Page-specific templates
	*/
	protected function setPageTemplates ($input = null) {
		$templates = array();

		// A flat array will do
		if ($input and is_array($input)) {
			$templates = $this->normalizePageTreeHash($input);
		}

		return $this->set('pageTemplates', $templates);
	}

	/**
	* Manual page order configuration - page ordering and page properties
	*/
	protected function setPageOrder ($input = null) {
		$results = array();

		// Normalize user input
		if (is_array($input) and !empty($input)) {
			$results = $this->normalizePageTreeStrings($input);
		}

		return $this->set('pageOrder', $results);
	}

	/**
	* Script files
	*/
	protected function setScripts () {
		return $this->set('scripts', $this->findAssetFiles($this->servant()->constants()->formats('scripts')));
	}

	/**
	* Server cache time (how old can a stored response be to be valid)
	*/
	protected function setServerCache ($input = null) {
		return $this->set('serverCache', $this->resolveCacheTime($input, $this->servant()->constants()->defaults('serverCache')));
	}

	/**
	* Path to site splash image from settings or remains an empty string
	*/
	protected function setSplashImage ($input = null) {
		return $this->set('splashImage', $this->resolveImageFile($input));
	}

	/**
	* Stylesheet files
	*/
	protected function setStylesheets () {
		return $this->set('stylesheets', $this->findAssetFiles($this->servant()->constants()->formats('stylesheets')));
	}

	/**
	* ID of selected template
	*
	* NOTE
	*   - Site setting is prefererred even when it's not actually available or have any files
	*   - In the absence of site setting, either global default or the first available template are used
	*
	* FLAG
	*   - Template should be page-specific
	*/
	protected function setTemplate ($input = null) {
		$input = trim(''.$input);
		$template = '';

		// Site settings
		if (!empty($input)) {
			$template = $input;
		}

		// Unavailable
		if (!$this->servant()->available()->template($template)) {
			$path = $this->servant()->paths()->templates('server');

			// Warn of missing template
			if ($this->servant()->debug() and !$this->servant()->available()->template($template)) {
				$this->warn('Attempted using the '.$template.' template, which is not available.');
			}

			// Try default
			$default = $this->servant()->constants()->defaults('template');
			if ($this->servant()->available()->template($default)) {
				$template = $default;

			// If default is unavailable, attempt to use whatever we have
			} else {
				$templates = glob_dir($path);
				if (!empty($templates)) {
					$template = basename($templates[0]);
				}
			}

		}

		return $this->set('template', $template);
	}



	/**
	* Private helpers
	*/

	private function findAssetFiles ($formats) {
		$files = array();

		// All files of this type in site's assets directory
		foreach (rglob_files($this->servant()->paths()->assets('server'), $formats) as $file) {
			$files[] = $this->servant()->paths()->format($file, false, 'server');
		}

		return $files;
	}

	private function normalizePageTreeHash ($input, $prefix = '') {
		$results = array();

		if (!empty($prefix)) {
			$prefix = suffix($prefix.'/');
		}

		foreach ($input as $key => $value) {
			$realKey = $prefix.unsuffix(unprefix($key, '/'), '/');

			// Acceptable value
			if (is_string($value) or is_numeric($value)) {
				$results[$realKey] = ''.$value;

			// Children
			} else if (is_array($value)) {
				$results = array_merge($results, $this->normalizePageTreeHash($value, $prefix.$realKey));
			}

		}

		return $results;
	}

	private function normalizePageTreeStrings ($strings, $prefix = '') {
		$results = array();

		if (!empty($prefix)) {
			$prefix = suffix($prefix.'/');
		}

		if (is_array($strings) and !empty($strings)) {
			foreach ($strings as $key => $value) {

				// String values go to results directly
				if (is_string($value)) {
					$results[] = $prefix.unsuffix(unprefix($value, '/'), '/');

				// Children
				} else if (is_array($value)) {
					$results[] = $prefix.$key;
					$results = array_merge($results, $this->normalizePageTreeStrings($value, is_string($key) ? $prefix.$key : $prefix));
				}

			}
		}

		return $results;
	}

	// Resolve valid cache time from user input, in minutes
	private function resolveCacheTime ($input, $default) {
		$result = calculate($default);

		// No explicit value given
		if ($input !== null and $input !== true) {

			// Cache disabled
			if (!$input) {
				$input = 0;

			// Formula as a string
			} elseif (is_string($input)) {
				$input = calculate($input, true);
			}

			// Numerical value available
			if (is_numeric($input)) {
				$result = $input;
			}

		}


		return max(0, intval($result));
	}

	private function resolveImageFile ($input) {
		$result = '';

		// A string will do
		if ($input and is_string($input)) {

			// Sanitize input
			$path = unprefix(unsuffix(trim_text($input, true), '/'), '/');

			// File format must be acceptable
			$extension = pathinfo($path, PATHINFO_EXTENSION);
			if (in_array($extension, $this->servant()->constants()->formats('iconImages'))) {

				// File must exist
				if (is_file($this->servant()->paths()->format($path, 'server'))) {
					$result = $path;
				}

			}

		}

		return $result;
	}

	private function readJsonFile ($path) {
		$result = array();

		// Look for a settings file
		if (is_file($path)) {

			// Read settings file as JSON, turn into an array
			$temp = json_decode(suffix(prefix(trim(file_get_contents($path)), '{'), '}'), true);
			if (is_array($temp)) {
				$result = $temp;
			}
			unset($temp);

		}

		return $result;
	}

}

?>