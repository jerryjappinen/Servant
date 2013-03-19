<?php

// Merge all scripts from theme
$output = '';
foreach ($servant->theme()->scripts('server') as $path) {
	$output .= file_get_contents($path);
}

// Merge all scripts from site
$output = '';
foreach ($servant->site()->scripts('server') as $path) {
	$output .= file_get_contents($path);
}

// Output scripts
$servant->action()->contentType('js')->output($output);
?>