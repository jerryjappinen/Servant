<?php

// Merge all stylesheets
$output = '';
foreach ($servant->theme()->stylesheets('server') as $path) {
	$output .= file_get_contents($path);
}

// Parse LESS
$output = trim($output);
if (!empty($output)) {
	$less = new lessc();

	// Compress when not in debug mode
	if ($servant->response()->browserCacheTime() > 0) {
		$less->setFormatter('compressed');
	}

	$output = $less->parse($output);
}

// Output CSS
$servant->action()->contentType('css')->output($output);

?>