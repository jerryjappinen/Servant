<?php

// Select page
$page = $servant->sitemap()->select()->page();

// Pointers
$code = 500;
try {
	$pointer = $input->pointer(0);
	$pointer = intval($pointer);
	if ($pointer >= 400 and $pointer < 600) {
		if ($servant->constants()->statuses($pointer)) {
			$code = $pointer;
		}
	}
} catch (Exception $e) {
	$code = 500;
}



// Error page via template
$message = '
	<h2>Something went wrong :(</h2>
	<p class="http-message">'.$servant->constants()->statuses($code).'</p>
';
$template = $action->nestTemplate($servant->site()->template(), $page, $message);



// Action output
$action->status($code)->contentType('html')->output($template);

?>