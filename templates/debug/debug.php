<?php
if ($servant->debug()) {

	$content = array(
		'Sitemap' => $servant->sitemap()->pages(2)->pick()->name(),
		'Action' => $action->id(),
		'Current page' => $page->name().' ('.implode('/', $page->tree()).')',
		'Templates' => $servant->available()->templates(),
		'Available template?' => $servant->available()->template('html'),
	);

	echo html_dump($content);
}
?>