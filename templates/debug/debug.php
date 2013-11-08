<?php
if ($servant->debug()) {

	$content = array(
		// $template->content(),
		'Defined variables' => array_keys(get_defined_vars()),
		'Action' => $action->id(),
		'Actions' => $servant->available()->actions(),
		'Available action?' => $servant->available()->action('error'),
		'Current page' => $page->name().' ('.implode('/', $page->tree()).')',
	);

	echo html_dump($content);
}
?>