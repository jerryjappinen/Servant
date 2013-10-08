<?php

/**
* Do basic validating routines
*
* DEPENDENCIES
*   - baseline.php
*/
class ValidatorRoutine {

	protected $pattern = '';



	/**
	* Constructor
	*/

	public function __construct () {
		return $this;
	}



	/**
	* Validation cycle
	*
	* NULL is never valid input. Upon any invalid input, null is always returned.
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
	* Final validation and smoke test. This should fail (return false) if content is unacceptable.
	*
	* Optional for each routine, pattern is automatically considered by default.
	*/
	protected function validInput ($input) {
		if ($this->pattern) {
			return preg_match($this->pattern, $input);
		}
		return true;
	}



}

?>