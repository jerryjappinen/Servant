<?php

// Level 2
$mainCategory = $page->parents(false, 0);
if ($mainCategory) {
	$sectionMenus[] = '';
	foreach ($mainCategory->children() as $node) {
		$listItem = '<a href="'.$node->endpoint('domain').'"><em>&#x25BE;</em>'.htmlspecialchars($node->name()).'</a>';
		if ($page->parents(false, 1) === $node or $page === $node) {
			$listItem = '<li class="selected"><strong>'.$listItem.'</strong></li>';
		} else {
			$listItem = '<li>'.$listItem.'</li>';
		}
		$sectionMenus[0] .= $listItem;
		unset($listItem);
	}
}

// Level 3
$subCategory = $page->parents(false, 1);
if ($subCategory) {
	$sectionMenus[] = '';
	foreach ($subCategory->children() as $node) {
		$listItem = '<a href="'.$node->endpoint('domain').'"><em>&#x25BE;</em>'.htmlspecialchars($node->name()).'</a>';
		if ($page->parents(false, 1) === $node or $page === $node) {
			$listItem = '<li class="selected"><strong>'.$listItem.'</strong></li>';
		} else {
			$listItem = '<li>'.$listItem.'</li>';
		}
		$sectionMenus[1] .= $listItem;
		unset($listItem);
	}
}

?>