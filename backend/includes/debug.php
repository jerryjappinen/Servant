<?php

/**
* Detect debug mode
*/
if (in_array($_SERVER['REMOTE_ADDR'], array('127.0.0.1', '::1'))) {
	$this->debug = true;
}

?>