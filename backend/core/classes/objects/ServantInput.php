<?php

/**
* Request input container
*/
class ServantInput extends ServantObject {



	/**
	* Properties
	*/
	protected $propertyRaw = null;
	protected $propertyValidate = null;



	/**
	* Use input
	*/
	public function fetch ($format, $key, $default = null) {
		$value = null;

		// Format must be valid
		if (!$this->validate()->available($format)) {
			$this->fail($format.' input is not supported.');

		// Validate raw input
		} else {
			$value = $this->validate()->$format($this->raw($key));
			if ($value === null) {
				$value = $default;
			}
		}

		// Fail on invalid input
		if ($value === null) {
			$this->fail('Invalid input provided for '.$key.' ('.$format.' required).');
		}

		return $value;
	}



	/**
	* Take input
	*/
	public function initialize () {

		// Merge inputs
		$arguments = func_get_args();
		$input = array();
		foreach (array_reverse($arguments) as $value) {

			// Normalize input
			if (empty($value)) {
				$value = array();
			} else {
				$value = to_array($value);
			}

			// Merge inputs (first parameters are prioritized)
			$input = array_merge($input, $value);

		}

		// Store raw input
		$this->setRaw($input);

		return $this;
	}



	/**
	* Public getters
	*/

	protected function formats () {
		$arguments = func_get_args();
		return call_user_func_array(array($this->validate(), 'available'), $arguments);
	}

	protected function validate () {
		return $this->getAndSet('validate');
	}



	/**
	* Setters
	*/

	protected function setRaw ($raw = array()) {
		return $this->set('raw', $raw);
	}

	protected function setValidate () {
		$this->servant()->utilities()->load('validator');
		return $this->set('validate', create_object('Validator'));
	}

}

?>