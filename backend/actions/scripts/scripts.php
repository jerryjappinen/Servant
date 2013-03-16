<?php

// Merge all scripts
$output = '';
foreach ($servant->theme()->scripts('server') as $path) {
	$output .= file_get_contents($path);
}

// Output scripts
$servant->action()->contentType('js')->output($output);
?>