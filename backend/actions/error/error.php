<?php

// Pointers
$code = 500;
try {
	if (isset($input)) {
		$pointer = $input->pointer(0);
		$pointer = intval($pointer);
		if ($pointer >= 400 and $pointer < 600) {
			if ($servant->constants()->statuses($pointer)) {
				$code = $pointer;
			}
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

// FLAG I can't know what content the template wants - I'm assuming the same as site action
$template = $servant->create()->template($servant->sitemap()->root()->template(), $message);



// Action output
$action->status($code)->contentType('html')->output($template->output());

?>