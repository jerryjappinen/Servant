<?php
// Development helpers

// Dump stuff into log
function dump ($value) {
	return "\n\n".var_export($value, true)."\n\n\n";
}

// Silently log to error log
function debug ($value) {
	$displayErrors = ini_get('display_errors');
	ini_set('display_errors', '0');
	error_log(dump($value), 0);
	ini_set('display_errors', $displayErrors);
}
?>