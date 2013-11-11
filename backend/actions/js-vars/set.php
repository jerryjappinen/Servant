<?php
// $action->contentType('html')->output($action->nestTemplate('html', '<div class="buffer even">'.html_dump($dump).html_dump($js).'</div>'));

// Compress
if (!$servant->debug()) {
	$servant->utilities()->load('jshrink');
	$js = Minifier::minify($js, array('flaggedComments' => false));
}

$action->status(200)->contentType('js')->output($js);
?>