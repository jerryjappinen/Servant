<?php

/**
* Level 1 menu
*/
$tempPages = $servant->pages()->level();
if (!empty($tempPages)) {

	foreach ($tempPages as $tempPage) {

		// Link to the page
		$link = '<a href="'.$tempPage->readPath('domain').'">'.$tempPage->categoryName(0).'</a>';

		// List item, possibly selected
		echo '<li>'.($tempPage->tree(0) === $page->tree(0) ? '<strong>'.$link.'</strong>' : $link).'</li>';

	}

}

?>