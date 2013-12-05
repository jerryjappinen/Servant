<?php

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

?>