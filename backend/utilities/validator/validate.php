<?php

/**
* Validate.php
*
* Released under MIT License
* Authored by Jerry Jäppinen
* http://eiskis.net/
* eiskis@gmail.com
*
* Compiled from source on 2013-10-10 14:14 UTC
*
* DEPENDENCIES
*
* baseline.php (included)
*   - http://eiskis.net/baseline-php/
*/



/**
* Main object for accessing validators
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



/**
* Basics for all validator routines
*/
class ValidatorRoutine {



	/**
	* Constructor
	*/

	public function __construct () {
		return $this;
	}



	/**
	* Validation cycle
	*
	* NOTE
	*
	* null is never valid input. Upon any invalid input, null is always returned.
	*/
	public function validate ($input) {
		$result = null;

		// NULL is never valid input
		if ($input !== null) {

			// Normalize type
			$input = $this->normalizeType($input);

			// Proceed if input is acceptable after normalization
			if ($input !== null and $this->validType($input)) {

				// Sanitize input
				$input = $this->sanitizeInput($input);

				// Final smoke test
				if ($this->validInput($input)) {
					$result = $input;
				}

			}

		}

		return $result;
	}



	/**
	* Type normalization
	*
	* Input can be converted to another, acceptable type if needed. Returns type-normalized input.
	*/
	protected function normalizeType ($input) {
		return $input;
	}

	/**
	* This should fail (return false) if type is unacceptable. Optional for each routine.
	*/
	protected function validType ($input) {
		return true;
	}

	/**
	* Sanitation. Returns sanitized input.
	*/
	protected function sanitizeInput ($input) {
		return $input;
	}

	/**
	* Final validation and smoke test. This should fail (return false) if content is unacceptable. Optional for each routine.
	*/
	protected function validInput ($input) {
		return true;
	}



}



/**
* Hashes (arrays)
*
* RESULT
* 	Type: Array
*/
class HashValidatorRoutine extends ValidatorRoutine {



	/**
	* Turn input into array if it makes sense
	*/
	protected function normalizeType ($input) {
		return to_array($input);
	}



	/**
	* Must be array
	*/
	protected function validType ($input) {
		return is_array($input);
	}



}



/**
* Strings
*
* RESULT
* 	Type: String
*/
class StringValidatorRoutine extends ValidatorRoutine {



	/**
	* Turn input into string if it makes sense
	*/
	protected function normalizeType ($input) {
		$result = null;

		// Strings
		if (is_string($input)) {
			$result = $input;

		// Numbers
		} else if (is_numeric($input)) {
			$result = ''.$input;

		// Booleans
		} else if (is_bool($input)) {
			$result = $input ? 'True': 'False';
		}

		return $result;
	}



	/**
	* Must be string
	*/
	protected function validType ($input) {
		return is_string($input);
	}



}



/**
* Flat hashes (arrays)
*
* RESULT
* 	Type: Array
*   Stripped: Child arrays
*/
class FlathashValidatorRoutine extends HashValidatorRoutine {



	/**
	* Children are normalized
	*/
	protected function sanitizeInput ($input) {
		return array_flatten($input, false, true);
	}



}



/**
* Lists (flat, indexed arrays)
*
* RESULT
* 	Type: Array
*   Stripped: Child arrays, keys
*/
class QueueValidatorRoutine extends HashValidatorRoutine {



	/**
	* Children are normalized, keys are removed
	*/
	protected function sanitizeInput ($input) {
		return array_flatten($input);
	}



}



/**
* Base64-formatted string (extend Strings)
*
* RESULT
* 	Type: String
* 	Stripped: newlines, excess whitespace
*/
class Base64ValidatorRoutine extends StringValidatorRoutine {



	/**
	* Trim whitespace and linebreaks
	*/
	protected function sanitizeInput ($input) {
		return preg_replace('/\s+/', '', $input);
	}



	/**
	* Must be Base64-compatible
	*/
	protected function validInput ($input) {
		return base64_decode($input, true) === false ? false : true;
	}


}



/**
* Fulltext (extend Strings)
*
* RESULT
* 	Type: String
* 	Stripped: excess whitespace
*/
class FulltextValidatorRoutine extends StringValidatorRoutine {



	/**
	* Trim whitespace
	*/
	protected function sanitizeInput ($input) {
		return trim_text($input);
	}



}



/**
* One-liner (extend Strings)
*
* RESULT
* 	Type: String
* 	Stripped: newlines, excess whitespace
*/
class OnelinerValidatorRoutine extends StringValidatorRoutine {



	/**
	* Trim whitespace and linebreaks
	*/
	protected function sanitizeInput ($input) {
		return trim_text($input, true);
	}



}

?>