<?php
if ($servant->debug()) {

	$content = array(
		'Data path' => $servant->paths()->dataOf('foo', 'server'),
		'Sitemap' => $servant->sitemap()->pages(2)->pick()->name(),
		'Current page' => $page->name().' ('.implode('/', $page->tree()).')',
		'Page ordering' => $servant->site()->pageOrder(),
		'Page names' => $servant->site()->pageNames(),
	);

	echo html_dump($content);
}
?>