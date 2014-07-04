<?php

/**
* Generates HTML main menu for this site
*/

$nodes = $servant->sitemap()->pages();
if (!empty($nodes)) {

	foreach ($nodes as $node) {

		// Link to the page
		$link = '<a href="'.$node->endpoint('domain').'">'.htmlspecialchars($node->name()).'</a>';

		// List item, possibly selected
		$mainmenu[] = '<li>'.($node->pointer(1) === $page->pointer(1) ? '<strong>'.$link.'</strong>' : $link).'</li>';

	}

}

?>