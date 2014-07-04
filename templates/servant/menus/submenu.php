<?php

/**
* Generates a submenu for this main category
*/

$mainCategory = $page->parents(false, 0);
if ($mainCategory) {

	$items = array();
	foreach ($mainCategory->children() as $node) {

		// Name
		$name = $node->name();

		// Children
		$submenuItems = '';
		$subItems = array();
		if ($node->children()) {

			// Rename category
			$name = $node->name();

			// Include all pages on this level
			foreach ($node->children() as $subNode) {

				// Child page HTML
				$url = $subNode->endpoint('domain');
				$listItem = '<a href="'.$url.'">'.htmlspecialchars($subNode->name()).'</a>';

				// Mark selected subNode
				if ($page->parents(false, 1) === $node and $page === $subNode) {
					$listItem = '<li class="selected"><strong>'.$listItem.'</strong>';
				} else {
					$listItem = '<li>'.$listItem;
				}

				// Close HTML
				$listItem .= '</li>';

				// Add item to submenu
				$subItems[] = $listItem;
				unset($listItem);
			}

			$submenuItems = '<ul>'.implode($subItems).'</ul>';
		}

		// Link HTML
		$url = $node->endpoint('domain');
		$listItem = '<a href="'.$url.'">'.htmlspecialchars($name).'</a>';

		// Mark selected page
		if ($page->parents(false, 1) === $node or $page === $node) {
			$listItem = '<li class="selected"><strong>'.$listItem.'</strong>';
		} else {
			$listItem = '<li>'.$listItem;
		}

		// Close HTML
		$listItem .= ($submenuItems ? $submenuItems : '').'</li>';

		// Add item to menu
		$items[] = $listItem;
		unset($listItem);

	}

	// Menu structure
	$submenu = '<ul>'.implode($items).'</ul>';

}

?>