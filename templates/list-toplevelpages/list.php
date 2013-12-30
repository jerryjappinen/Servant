<?php

/**
* Generates HTML main menu for this site
*
* NESTED TEMPLATES
*	(none)
*
* CONTENT PARAMETERS
*	0: Current page (ServantPage)
*/

$page = $template->content();

$nodes = $servant->sitemap()->pages();
if (!empty($nodes)) {

	foreach ($nodes as $node) {

		// Link to the page
		$link = '<a href="'.$node->endpoint('domain').'">'.htmlspecialchars($node->name()).'</a>';

		// List item, possibly selected
		echo '<li>'.($node->pointer(1) === $page->pointer(1) ? '<strong>'.$link.'</strong>' : $link).'</li>';

	}

}
?>