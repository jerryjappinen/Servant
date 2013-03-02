<?php

class ServantSettings extends ServantObject {

	// Properties
	protected $propertyNamingConvention = null;
	protected $propertyTemplateLanguages = null;

	// Public getters
	public function namingConvention () {
		return $this->getAndSet('namingConvention', func_get_args());
	}
	public function templateLanguages () {
		return $this->getAndSet('templateLanguages', func_get_args());
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
		return $this->set('namingConvention', to_array($value));
	}
	protected function setTemplateLanguages ($value = array()) {
		return $this->set('templateLanguages', to_array($value));
	}



	// Private helpers

	// Settable properties
	private function properties () {
		return array(
			'namingConvention',
			'templateLanguages',
		);
	}

}

?>