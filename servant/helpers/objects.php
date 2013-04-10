<?php

// Shorthand for chainable object creation
function create ($object) {
	return $object;
}



// Run scripts files cleanly (no visible variables left)
// Argument 1: path to a file
// Argument 2: array of variables and values to be created for the script
// Returns the contents of the output buffer after the script has run
function run_script () {

	if (is_file(func_get_arg(0))) {

		// Set up variables for the script
		foreach (func_get_arg(1) as $__key => $__value) {
			if (is_string($__key)) {
				${$__key} = $__value;
			}
		}

		// Clean up variables
		if (!array_key_exists('__key', func_get_arg(1))) {
			unset($__key);
		}
		if (!array_key_exists('__value', func_get_arg(1))) {
			unset($__value);
		}

		// Run each script
		ob_start();

		// Include script
		include func_get_arg(0);

		// Catch output reliably
		$output = ob_get_contents();
		if ($output === false) {
			$output = '';
		}

		// Clear buffer
		ob_end_clean();

	}

	// Return any output
	return $output;
}

?>