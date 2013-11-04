<?php
if ($servant->debug()) {

	$content = array(
		// $template->content(),
		'Action: '.$action->id(),
		'Current page: '.implode('/', $servant->pages()->current()->tree()),
	);

	if (empty($content[0])) {
		array_shift($content);
	}

	echo html_dump($content);
}
?>