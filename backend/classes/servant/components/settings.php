<?php

class ServantSettings extends ServantObject {

	// Properties
	protected $propertyCache 			= null;
	protected $propertyContentTypes 	= null;
	protected $propertyDefaults 		= null;
	protected $propertyFormats 			= null;
	protected $propertyNamingConvention = null;
	protected $propertyPatterns 		= null;
	protected $propertyStatusCodes 		= null;

	// Public getters
	public function cache () {
		return $this->getAndSet('cache', func_get_args());
	}
	public function contentTypes () {
		return $this->getAndSet('contentTypes', func_get_args());
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
	public function patterns () {
		return $this->getAndSet('patterns', func_get_args());
	}
	public function statusCodes () {
		return $this->getAndSet('statusCodes', func_get_args());
	}



	// Take original settings in during initialization
	public function initialize ($settings) {

		// This is what we can set
		$properties = array(
			'cache',
			'contentTypes',
			'defaults',
			'formats',
			'namingConvention',
			'patterns',
			'statusCodes',
		);

		// Run setters if values are given
		foreach ($properties as $key) {
			$parameters = array();
			if (isset($settings[$key]) and !empty($settings[$key])) {
				$parameters[] = to_array($settings[$key]);
			}
			$this->callSetter($key, $parameters);
		}

		return $this;
	}



	// Setters

	// Cache'related items
	protected function setCache ($input = null) {

		// Base format, with defaults
		$results = array(
			'browser' => 0,
			'server' => 0,
		);

		// Pick cache settings into properly formatted array
		if ($input) {
			foreach ($results as $key => $value) {
				if (isset($input[$key])) {

					// Don't accept just anything
					if (is_int($input[$key]) and $input[$key] > 0) {
						$results[$key] = strval($input[$key]);
					}

				}
			}
		}

		return $this->set('cache', $results);
	}



	// Content types
	protected function setContentTypes ($input = null) {
		return $this->set('contentTypes', $this->takeInFlattenedArray($input, false));
	}



	// Default preferences for guiding how to choose between available items
	// NOTE default items are not necessarily available in the system, they're just dumb preferences
	protected function setDefaults ($input = null) {

		// Base format
		$results = array(
			'site' => null,
			'template' => null,
			'theme' => null,
		);

		// Pick default IDs
		if ($input) {
			foreach ($results as $key => $value) {
				if (isset($input[$key])) {

					// Normalize subarray
					if (is_array($input[$key])) {
						$keys = array_keys($input[$key]);
						$input[$key] = $input[$key][$keys[0]];
					}

					// Set default
					$results[$key] = strval($input[$key]);
				}
			}
		}

		return $this->set('defaults', $results);
	}



	protected function setFormats ($input = null) {

		// Base format with defaults
		$results = array(
			'templates' => array(),
			'stylesheets' => array(),
			'scripts' => array(),
		);

		// Pick format arrays
		if ($input) {
			foreach ($results as $key => $value) {
				if (isset($input[$key])) {
					$results[$key] = array_flatten(to_array($input[$key]));
				}
			}
		}

		return $this->set('formats', $results);
	}



	protected function setNamingConvention ($input = array()) {
		return $this->set('namingConvention', array_flatten(to_array($input), true, true));
	}



	protected function setPatterns ($input = null) {
		return $this->set('patterns', $this->takeInFlattenedArray($input, false));
	}



	protected function setStatusCodes ($input = null) {
		return $this->set('statusCodes', $this->takeInFlattenedArray($input, true));
	}



	// Private helpers

	private function takeInFlattenedArray ($array = null, $numericalKeys = false) {
		$results = array();

		// Pick stuff from input
		if ($array and is_array($array)) {
			$array = array_flatten($array, true, true);
			foreach ($array as $key => $value) {

				// Accept string values with either numerical or integer keys
				if (is_string($value) and (($numericalKeys and is_numeric($key)) or (!$numericalKeys and is_string($key)))) {
					if ($numericalKeys) {
						$key = intval($key);
					}
					$results[$key] = $value;
				}

			}
		}

		return $results;
	}

}

?>