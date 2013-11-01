<?php

/**
* Level 1 menu
*/
$pages = $servant->pages()->level();
if (!empty($pages)) {

	foreach ($pages as $page) {

		// Link to the page
		$link = '<a href="'.$page->readPath('domain').'">'.$page->categoryName(0).'</a>';

		// List item, possibly selected
		echo '<li>'.($page->tree(0) === $servant->pages()->current()->tree(0) ? '<strong>'.$link.'</strong>' : $link).'</li>';

	}

}

?>