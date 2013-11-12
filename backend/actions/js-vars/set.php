<?php

// Compress
if (!$servant->debug()) {
	$servant->utilities()->load('jshrink');
	$js = Minifier::minify($js, array('flaggedComments' => false));
}

$action->status(200)->contentType('js')->output($js);

// Debug
// $action->contentType('html')->output($action->nestTemplate('html', '<div class="buffer even">'.(isset($dump) ? html_dump($dump) : '').html_dump($js).'</div>'));

?>