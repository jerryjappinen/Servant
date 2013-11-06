<?php

// All scripts for site
$output = '';

// Merge all scripts from theme
foreach ($servant->theme()->scripts('server') as $path) {
	$output .= file_get_contents($path);
}

/**
* Merge scripts from site
*
* FLAG
*   - We only want these in read action (we should print this upon request only - needs input support)
*/
foreach ($page->scripts('server') as $path) {
	$output .= file_get_contents($path);
}

// Compress
if (!$servant->debug()) {
	$servant->utilities()->load('jshrink');
	$output = Minifier::minify($output, array('flaggedComments' => false));
}

// Output scripts
$action->contentType('js')->output($output);
?>