<?php

$footer = '<h1><a href="'.$servant->paths()->root('domain').'" data-target="body" class="plain scroll">This is Servant</a></h1>';

$mainmenu = $template->nest('list-toplevelpages');

$submenu = $template->nest('list-submenu');

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

?>