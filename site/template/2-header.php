<?php

// Header
$output = '<div class="frame-header"><div class="frame-container">

	<h1><a href="'.$servant->paths()->root('domain').'">'.$servant->site()->name().'</a></h1>';



	// Level 1 menu
	$pages = $servant->pages()->level();
	if ($pages) {
		$output .= '<div id="responsive-menu"><ul class="menu-1">';
		foreach ($pages as $index => $page) {

			// Link in a list item, possibly selected
			$link = '<a href="'.$servant->paths()->userAction('read', 'domain', $page->tree()).'">'.$page->name().'</a>';
			$output .= '<li>'.($page->tree(0) === $servant->pages()->current()->tree(0) ? '<strong>'.$link.'</strong>' : $link).'</li>';

		}
		$output .= '</ul></div>';
	}
	unset($pages, $index, $link, $page);



// End header
$output .= '<div class="clear"></div></div></div>';

echo $output;
?>