<?php
if ($servant->debug()) {

	$content = array(
		'Page ordering' => $servant->site()->pageOrder(),
		'Page names' => $servant->site()->pageNames(),
	);

	echo html_dump($content);
}
?>