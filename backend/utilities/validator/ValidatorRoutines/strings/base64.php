<?php

/**
* Base64-formatted string (extend Strings)
*
* RESULT
* 	Type: String
* 	Stripped: newlines, excess whitespace
*/
class Base64ValidatorRoutine extends StringValidatorRoutine {

	protected $pattern = '^(?:[A-Za-z0-9+/]{4})*(?:[A-Za-z0-9+/]{2}==|[A-Za-z0-9+/]{3}=)?$';



	/**
	* Trim whitespace and linebreaks
	*/
	protected function sanitizeInput ($input) {
		return trim_text($input, true);
	}



}

?>