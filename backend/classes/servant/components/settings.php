<?php

class ServantSettings extends ServantObject {

	// Properties
	protected $propertyNamingConvention = null;
	protected $propertyFormats 			= null;

	// Public getters
	public function namingConvention () {
		return $this->getAndSet('namingConvention', func_get_args());
	}
	public function formats () {
		return $this->getAndSet('formats', func_get_args());
	}



	// Take original settings in during initialization
	public function initialize ($settings) {
		$results = array();

		// Run setters if values are given
		foreach ($this->properties() as $key) {
			$parameters = array();
			if (isset($settings[$key]) and !empty($settings[$key])) {
				$parameters[] = $settings[$key];
			}
			$this->callSetter($key, $parameters);
		}

		return $this;
	}



	// Setters
	protected function setNamingConvention ($value = array()) {
		return $this->set('namingConvention', array_flatten(to_array($value), true, true));
	}

	protected function setFormats ($formats = null) {

		// Base format
		$results = array(
			'templates' => array(),
			'stylesheets' => array(),
			'scripts' => array()
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



	// Private helpers

	// Settable properties
	private function properties () {
		return array(
			'namingConvention',
			'formats',
		);
	}

}

?>