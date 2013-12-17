<?php

/**
* Level 1 menu
*/
$nodes = $servant->sitemap()->pages();
if (!empty($nodes)) {

	foreach ($nodes as $node) {

		// Link to the page
		$link = '<a href="'.$node->endpoint('domain').'">'.htmlspecialchars($node->name()).'</a>';

		// List item, possibly selected
		echo '<li>'.($node->tree(1) === $page->tree(1) ? '<strong>'.$link.'</strong>' : $link).'</li>';

	}

}
?>