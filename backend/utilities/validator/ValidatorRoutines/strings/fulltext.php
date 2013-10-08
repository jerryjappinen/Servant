<?php

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

?>