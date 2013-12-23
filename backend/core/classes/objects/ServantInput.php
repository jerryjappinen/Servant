<?php

/**
* Request input container
*
* DEPENDENCIES
*   ???
*/
class ServantInput extends ServantObject {



	/**
	* Properties
	*/
	protected $propertyPointer 	= null;
	protected $propertyRaw 		= null;
	protected $propertyValidate = null;



	/**
	* Initialization
	*/
	public function initialize () {

		// Merge inputs, first one is treated as most important
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
		$this->setRaw(array_reverse($input));

		return $this;
	}



	/**
	* Convenience
	*/

	public function formats () {
		$arguments = func_get_args();
		return call_user_func_array(array($this->validate(), 'available'), $arguments);
	}

	public function fetch ($key, $format, $default = null) {
		$value = null;

		// FLAG hardcoded pointer key, weird behavior
		if ($key === 'pointer') {
			$this->fail('Use ServantInput->pointer() to access pointer parameters.');

		} else {

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

		}

		return $value;
	}

	// Create a string representation of this input
	// FLAG should be somewhere else
	public function serialize () {
		$result = '';
		$raw = $this->raw();

		// Serialize & encode raw input
		if (!empty($raw)) {
			$result = base64_encode(serialize($raw));
		}

		return $result;
	}

	// Undo a serialization a string back
	// FLAG should be somewhere else
	public function unserialize ($string = '') {
		$result = array();

		// Exploding serialized data
		if (!empty($string)) {
			$result = unserialize(base64_decode($string));
		}

		return $result;
	}






	/**
	* Getters
	*/

	public function pointer ($index = null, $includeAction = null) {
		$pointer = $this->getAndSet('pointer');

		// Normalize parameters
		if (is_bool($index)) {
			$temp = $includeAction;
			$includeAction = $index;
			$index = $temp;
			unset($temp);
		}

		// Skip action
		if (!$includeAction) {
			array_shift($pointer);
		}

		// Get specific value
		if ($index !== null) {

			// Default to empty string
			$result = '';
			if (array_key_exists($index, $pointer)) {
				$result = $pointer[$index];
			}

		// Full pointer
		} else {
			$result = $pointer;
		}

		return $result;
	}

	protected function raw () {
		$arguments = func_get_args();
		return $this->getAndSet('raw', $arguments);
	}

	public function validate () {
		return $this->getAndSet('validate');
	}



	/**
	* Setters
	*/

	protected function setPointer () {

		// Fetch user input
		$pointer = $this->raw('pointer');

		// Normalize type
		if (!$pointer) {
			$pointer = '';

		} else if (is_array($pointer)) {
			$pointer = array_flatten($pointer);
		}

		return $this->set('pointer', $this->validate()->ids($pointer));
	}

	protected function setRaw ($raw = array()) {
		return $this->set('raw', $raw);
	}

	protected function setValidate () {
		$this->servant()->utilities()->load('validator');
		return $this->set('validate', create_object('Validator'));
	}

}

?>