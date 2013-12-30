<?php

// Menus
$mainmenu = $template->nest('list-toplevelpages', $page);
$submenu = $template->nest('list-submenu', $page);
$sectionmenus = array();

// Level 2
$mainCategory = $page->parents(false, 0);
if ($mainCategory) {
	$sectionmenus[] = '';
	foreach ($mainCategory->children() as $node) {
		$listItem = '<a href="'.$node->endpoint('domain').'">'.htmlspecialchars($node->name()).'</a>';
		if ($page->parents(false, 1) === $node or $page === $node) {
			$listItem = '<li class="selected"><strong>'.$listItem.'</strong></li>';
		} else {
			$listItem = '<li>'.$listItem.'</li>';
		}
		$sectionmenus[0] .= $listItem;
		unset($listItem);
	}
}

// Level 3
$subCategory = $page->parents(false, 1);
if ($subCategory) {
	$sectionmenus[] = '';
	foreach ($subCategory->children() as $node) {
		$listItem = '<a href="'.$node->endpoint('domain').'">'.htmlspecialchars($node->name()).'</a>';
		if ($page->parents(false, 1) === $node or $page === $node) {
			$listItem = '<li class="selected"><strong>'.$listItem.'</strong></li>';
		} else {
			$listItem = '<li>'.$listItem.'</li>';
		}
		$sectionmenus[1] .= $listItem;
		unset($listItem);
	}
}

// Next and previous links
$prevnext = '';
if ($page->depth() > 0) {

	// Previous
	$previous = $page->previous();
	if ($previous) {
		$prevnext .= '<li class="previous"><a href="'.$previous->endpoint('domain').'">'.$previous->name().'</a></li>';
	}

	// Next
	$next = $page->next();
	if ($next) {
		$prevnext .= '<li class="next"><a href="'.$next->endpoint('domain').'">'.$next->name().'</a></li>';
	}

	$prevnext = $prevnext ? '<div class="prevnext clear-after"><ul class="plain collapse">'.$prevnext.'</ul></div>' : '';
}

$footer = '<h1><a href="'.$servant->paths()->root('domain').'" data-target="body" class="plain scroll">This is Servant<span class="inline-block pull-right">.</span></a></h1>';

?>