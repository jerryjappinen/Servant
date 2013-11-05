<?php
if ($servant->debug()) {

	$content = array(
		// $template->content(),
		'Defined vars' => array_keys(get_defined_vars()),
		'Action' => $action->id(),
		'Current page' => implode('/', $servant->pages()->current()->tree()),
	);

	echo '<h3>Template scope ('.$template->id().')</h3>'.html_dump($content);
}
?>