<?php

class ServantSettings extends ServantObject {

	// Properties
	protected $propertyCache 			= null;
	protected $propertyDefaults 		= null;
	protected $propertyFormats 			= null;
	protected $propertyNamingConvention = null;

	// Public getters
	public function cache () {
		return $this->getAndSet('cache', func_get_args());
	}
	public function defaults () {
		return $this->getAndSet('defaults', func_get_args());
	}
	public function formats () {
		return $this->getAndSet('formats', func_get_args());
	}
	public function namingConvention () {
		return $this->getAndSet('namingConvention', func_get_args());
	}



	// Take original settings in during initialization
	public function initialize ($settings) {

		// This is what we can set
		$properties = array(
			'cache',
			'defaults',
			'formats',
			'namingConvention',
		);

		// Run setters if values are given
		foreach ($properties as $key) {
			$parameters = array();
			if (isset($settings[$key]) and !empty($settings[$key])) {
				$parameters[] = $settings[$key];
			}
			$this->callSetter($key, $parameters);
		}

		return $this;
	}



	// Setters

	// Cache'related items
	protected function setCache ($cache = null) {

		// Base format, with defaults
		$results = array(
			'browser' => 0,
		);

		// Pick cache settings into properly formatted array
		if ($cache and is_array($cache)) {
			foreach ($results as $key => $value) {
				if (isset($cache[$key])) {

					// Don't accept just anything
					if (is_int($cache[$key]) and $cache[$key] > 0) {
						$results[$key] = strval($cache[$key]);
					}

				}
			}
		}

		return $this->set('cache', $results);
	}

	// Default preferences for guiding how to choose between available items
	// NOTE default items are not necessarily available in the system, they're just dumb preferences
	protected function setDefaults ($defaults = null) {

		// Base format
		$results = array(
			'site' => null,
			'template' => null,
			'theme' => null,
		);

		// Pick defaults into properly formatted array
		if ($defaults and is_array($defaults)) {
			foreach ($results as $key => $value) {
				if (isset($defaults[$key])) {

					// Normalize subarray
					if (is_array($defaults[$key])) {
						$keys = array_keys($defaults[$key]);
						$defaults[$key] = $defaults[$key][$keys[0]];
					}

					// Set default
					$results[$key] = strval($defaults[$key]);
				}
			}
		}

		return $this->set('defaults', $results);
	}

	protected function setFormats ($formats = null) {

		// Base format with defaults
		$results = array(
			'templates' => array(),
			'stylesheets' => array(),
			'scripts' => array(),
		);

		// Pick settings into properly formatted array
		if ($formats and is_array($formats)) {
			foreach ($results as $key => $value) {
				if (isset($formats[$key])) {
					$results[$key] = array_flatten(to_array($formats[$key]));
				}
			}
		}

		return $this->set('formats', $results);
	}

	protected function setNamingConvention ($value = array()) {
		return $this->set('namingConvention', array_flatten(to_array($value), true, true));
	}



}

?>