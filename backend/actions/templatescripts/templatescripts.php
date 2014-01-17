<?php

/**
* Output template-specific scripts
*/
$output = '';
$template = $input->pointer(0);
if ($template and $servant->available()->template($template)) {

	// Find files
	$templateDir = $servant->create()->template($template)->path('server');
	foreach (rglob_files($templateDir, $this->servant()->constants()->formats('scripts')) as $file) {
		$output .= file_get_contents($file);
	}

	// Compress
	if (!$servant->debug()) {
		$servant->utilities()->load('jshrink');
		$output = Minifier::minify($output, array('flaggedComments' => false));
	}

}

// Output scripts
$action->contentType('js')->output($output);
?>