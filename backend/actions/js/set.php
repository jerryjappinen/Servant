<?php

// Compress
if (!$servant->debug()) {
	$servant->utilities()->load('jshrink');
	$output = Minifier::minify($output, array('flaggedComments' => false));
}

$action->status(200)->contentType('js')->output($output);

?>