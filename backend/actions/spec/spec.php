<?php
$servant->utilities()->load('unitest');
$action->contentType('json');

// Run tests
$path = $servant->paths()->root('server').'tests/';
if (is_dir($path)) {
	$u = create_object('Unitest')->inject('servant', $servant)->scrape($path);
	$action->output(str_replace('\\/', '/', json_encode($u->run(), JSON_PRETTY_PRINT)));

// Not available
} else {
	$action->status(404)->output('"No tests found."');
}
?>