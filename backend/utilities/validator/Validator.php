<?php

/**
* Do basic validating routines
*
* NOTE
* 	NULL is never valid input. Upon any invalid input, null is always returned.
*
* DEPENDENCIES
*   - baseline.php
*/
class Validator {

	/**
	* Available items
	*/

	private $availableList = array(
		'string',
			'base64',
			'fulltext',
			'oneliner',
		'hash',
			'flathash',
			'queue',
	);

	public function available ($assert = null) {
		$list = $this->availableList;
		if ($assert) {
			return in_array($assert, $list);
		}
		return $list;
	}



	/**
	* Interface
	*/
	public function string ($input) {
		return $this->validate('string', $input);
	}
	public function passesAsString ($input) {
		return $this->passes('string', $input);
	}
		public function base64 ($input) {
			return $this->validate('base64', $input);
		}
		public function passesAsBase64 ($input) {
			return $this->passes('base64', $input);
		}
		public function fulltext ($input) {
			return $this->validate('fulltext', $input);
		}
		public function passesAsFulltext ($input) {
			return $this->passes('fulltext', $input);
		}
		public function oneliner ($input) {
			return $this->validate('oneliner', $input);
		}
		public function passesAsOneliner ($input) {
			return $this->passes('oneliner', $input);
		}
	public function hash ($input) {
		return $this->validate('hash', $input);
	}
	public function passesAsHash ($input) {
		return $this->passes('hash', $input);
	}
		public function flathash ($input) {
			return $this->validate('flathash', $input);
		}
		public function passesAsFlathash ($input) {
			return $this->passes('flathash', $input);
		}
		public function queue ($input) {
			return $this->validate('queue', $input);
		}
		public function passesAsQueue ($input) {
			return $this->passes('queue', $input);
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