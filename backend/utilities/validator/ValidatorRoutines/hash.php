<?php

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

?>