<?php

class ServantSite extends ServantObject {

	/**
	* Properties
	*/
	protected $propertyIcon 		= null;
	protected $propertyLanguage 	= null;
	protected $propertyName 		= null;
	protected $propertySettings		= null;



	/**
	* FLAG legacy
	*/

	public function page () {
		$arguments = func_get_args();
		return call_user_func_array(array($this->servant()->pages(), 'current'), $arguments);
	}
	public function pages () {
		$arguments = func_get_args();
		return call_user_func_array(array($this->servant()->pages(), 'files'), $arguments);
	}
	public function path () {
		$arguments = func_get_args();
		return call_user_func_array(array($this->servant()->pages(), 'path'), $arguments);
	}



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

}

?>