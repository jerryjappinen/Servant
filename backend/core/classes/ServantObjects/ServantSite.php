<?php

class ServantSite extends ServantObject {

	/**
	* Properties
	*/
	protected $propertyBrowserCache = null;
	protected $propertyDescription 	= null;
	protected $propertyIcon 		= null;
	protected $propertyLanguage 	= null;
	protected $propertyName 		= null;
	protected $propertyPageNames 	= null;
	protected $propertyServerCache 	= null;
	protected $propertySplashImage 	= null;
	protected $propertyTemplate 	= null;



	/**
	* Take original settings in during initialization (all are optional)
	*/
	public function initialize () {

		// Read settings from JSON
		$manifest = $this->readJsonFile($this->servant()->paths()->manifest('server'));
		if ($manifest) {

			// This is what we can set
			$properties = array(
				'browserCache',
				'description',
				'icon',
				'language',
				'name',
				'pageNames',
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
	* Public getters
	*/

	public function icon ($format = null) {
		$path = $this->getAndSet('icon');
		if ($format and !empty($path)) {
			$path = $this->servant()->format()->path($path, $format);
		}
		return $path;
	}

	public function splashImage ($format = null) {
		$path = $this->getAndSet('splashImage');
		if ($format and !empty($path)) {
			$path = $this->servant()->format()->path($path, $format);
		}
		return $path;
	}



	/**
	* Setters
	*/

	/**
	* Browser cache time (used in cache headers)
	*/
	protected function setBrowserCache ($input = null) {
		return $this->set('browserCache', $this->resolveCacheTime($input, $this->servant()->settings()->defaults('browserCache')));
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
			$globalDefault = $this->servant()->settings()->defaults('language');
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
	*   - Hardcoded default name (-> add to settings()->defaults())
	*/
	protected function setName ($input = null) {
		$result = 'Home';

		// A string will do
		if ($input and is_string($input)) {
			$result = $input;
		}

		return $this->set('name', trim_text($result, true));
	}

	/**
	* Overrides for page naming
	*/
	protected function setPageNames ($input = null) {
		$pageNames = array();

		// A flat array will do
		if ($input and is_array($input)) {
			$pageNames = array_flatten($input, false, true);
		}

		return $this->set('pageNames', $pageNames);
	}

	/**
	* Server cache time (how old can a stored response be to be valid)
	*/
	protected function setServerCache ($input = null) {
		return $this->set('serverCache', $this->resolveCacheTime($input, $this->servant()->settings()->defaults('serverCache')));
	}

	/**
	* Path to site splash image from settings or remains an empty string
	*/
	protected function setSplashImage ($input = null) {
		return $this->set('splashImage', $this->resolveImageFile($input));
	}

	/**
	* ID of selected template
	*
	* NOTE
	*   - Site setting is prefererred even when it's not actually available or have any files
	*   - In the absence of site setting, either global default or the first available template are used
	*/
	protected function setTemplate ($input = null) {
		$input = trim(''.$input);
		$template = '';

		// Site settings
		if (!empty($input)) {
			$template = $input;

		} else {
			$path = $this->servant()->paths()->templates('server');

			// Try default
			// FLAG servant()->templates()->available() would be handy here
			$default = $this->servant()->settings()->defaults('template');
			if (!empty($default) and is_dir(suffix($path.$default, '/'))) {
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

	// Resolve valid cache time from user input, in minutes
	private function resolveCacheTime ($input, $default) {
		$result = $default;

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
			if (in_array($extension, $this->servant()->settings()->formats('iconImages'))) {

				// File must exist
				if (is_file($this->servant()->format()->path($path, 'server'))) {
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