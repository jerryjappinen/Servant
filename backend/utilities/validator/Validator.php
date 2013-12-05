<?php

/**
* Main object for accessing validators
*/
class Validator {

	/**
	* Available items
	*/

	// Can I automate this?
	private $availableList = array(
		'string',
			'base64',
			'fulltext',
			'oneliner',
		'hash',
			'flathash',
			'queue',
	);

	// List available routines, or check if a specific one is available
	public function available ($routine = null) {
		$list = $this->availableList;
		if ($routine) {
			return in_array($routine, $list);
		}
		return $list;
	}



	/**
	* Interface
	*
	* We expect users to call a validation routine by default
	*/
	public function __call ($routine, $arguments) {
		if ($this->available($routine)) {
			array_unshift($arguments, $routine);
			return call_user_func_array(array($this, 'validate'), $arguments);
		} else {
			throw new Exception('Unavailable');
			
		}
	}



	/**
	* Core behavior
	*/

	// See if will validate
	private function passes ($targetFormat, $input) {
		return $this->validate($targetFormat, $input) !== null;
	}

	// Run all behaviors to see if an input value passes the tests or passes back null
	private function validate ($targetFormat, $input) {
		return $this->createRoutine($targetFormat)->validate($input);
	}



	/**
	* Private helpers
	*/

	// Create a new routine object
	private function createRoutine ($targetFormat) {
		$className = $targetFormat.'ValidatorRoutine';

		// Make sure the routine is available
		if ($this->available($targetFormat) and class_exists($className)) {
			$object = new $className($this);
			if (is_subclass_of($object, 'ValidatorRoutine')) {
				$result = $object;
			}
		}

		// Throw exception if it doesn't
		if (!isset($result)) {
			fail('No validator routine available for "'.$targetFormat.'"');
		}

		return $result;
	}



}

?>