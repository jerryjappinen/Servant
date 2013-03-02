<?php

// Fail
function fail ($message, $code = null) {
	debug(isset($this) ? $this : 'null');
	throw new Exception($message, isset($code) ? $code : 500);
}

?>