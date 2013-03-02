<?php

class ServantSettings extends ServantObject {

	// Properties
	protected $propertyTemplateLanguages = null;

	// Public getters
	public function templateLanguages () {
		return $this->getAndSet('templateLanguages', func_get_args());
	}

	// Take original settings in during initialization
	public function initialize ($settings) {
		$results = array();

		// This is what we can set
		$keys = array(
			'templateLanguages',
		);

		// Run setters if setting values are given
		foreach ($keys as $key) {
			if (isset($settings[$key]) and !empty($settings[$key])) {
				call_user_func_array(array($this, $this->setterName($key)), $settings[$key]);
			}
		}

		return $this;
	}



	// Setters
	protected function setTemplateLanguages () {
		return $this->set('templateLanguages', func_get_args());
	}

}

?>