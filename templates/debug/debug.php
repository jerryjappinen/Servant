<?php
if ($servant->debug()) {

	$pageOrder = array();
	foreach ($servant->site()->pageOrder() as $key => $value) {
		$section = substr($value, 0, strrpos($value, '/'));
		$pageOrder['root'.($section ? '/'.$section : '')][] = unprefix($value, $section.'/');
	}

	// $pageOrder = $servant->site()->pageOrder();

	$content = array(
		'Page ordering' => $pageOrder,
		'Page names' => $servant->site()->pageNames(),
	);

	echo html_dump($content);
}
?>