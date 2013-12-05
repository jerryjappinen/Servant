<?php

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

?>