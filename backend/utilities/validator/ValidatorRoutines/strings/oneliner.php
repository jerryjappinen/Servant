<?php

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