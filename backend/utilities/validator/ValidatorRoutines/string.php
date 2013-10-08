<?php

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

?>