<?php

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

?>