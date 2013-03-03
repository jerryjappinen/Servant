<?php

class ServantSettings extends ServantObject {

	// Properties
	protected $propertyNamingConvention = null;
	protected $propertyStylesheetFiles = null;
	protected $propertyTemplateFiles = null;

	// Public getters
	public function namingConvention () {
		return $this->getAndSet('namingConvention', func_get_args());
	}
	public function stylesheetFiles () {
		return $this->getAndSet('stylesheetFiles', func_get_args());
	}
	public function templateFiles () {
		return $this->getAndSet('templateFiles', func_get_args());
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
	protected function setStylesheetFiles ($value = array()) {
		return $this->set('stylesheetFiles', array_flatten(to_array($value)));
	}
	protected function setTemplateFiles ($value = array()) {
		return $this->set('templateFiles', array_flatten(to_array($value)));
	}



	// Private helpers

	// Settable properties
	private function properties () {
		return array(
			'namingConvention',
			'stylesheetFiles',
			'templateFiles',
		);
	}

}

?>