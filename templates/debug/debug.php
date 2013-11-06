<?php
if ($servant->debug()) {

	$content = array(
		// $template->content(),
		'Defined variables' => array_keys(get_defined_vars()),
		'Action' => $action->id(),
		'Current page' => $page->name().' ('.implode('/', $page->tree()).')',
	);

	echo html_dump($content);
}
?>