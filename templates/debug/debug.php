<?php
if ($servant->debug()) {

	$content = array(
		// $template->content(),
		'Defined variables' => array_keys(get_defined_vars()),
		'Action' => $action->id(),
		'Current page' => implode('/', $servant->pages()->current()->tree()),
	);

	echo html_dump($content);
}
?>