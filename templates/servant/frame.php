<?php

/**
* Submenu for read action
*/
$menu = '';
if ($action->isRead()) {

	// Generate menu if needed
	$mainCategory = $page->parents(false, 0);
	if ($mainCategory) {

		$items = array();
		foreach ($mainCategory->children() as $node) {

			// Name
			$name = $node->name();

			// Children
			$submenu = '';
			$subItems = array();
			if ($node->children()) {

				// Rename category
				$name = $node->name();

				// Include all pages on this level
				foreach ($node->children() as $subNode) {

					// Child page HTML
					$url = $subNode->endpoint('domain');
					$listItem = '<a href="'.$url.'">'.$subNode->name().'</a>';

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

				$submenu = '<ul class="menu-3">'.implode($subItems).'</ul>';
			}

			// Link HTML
			$url = $node->endpoint('domain');
			$listItem = '<a href="'.$url.'">'.$name.'</a>';

			// Mark selected page
			if ($page->parents(false, 1) === $node or $page === $node) {
				$listItem = '<li class="selected"><strong>'.$listItem.'</strong>';
			} else {
				$listItem = '<li>'.$listItem;
			}

			// Close HTML
			$listItem .= ($submenu ? $submenu : '').'</li>';

			// Add item to menu
			$items[] = $listItem;
			unset($listItem);

		}

		// Menu structure
		$menu = '<ul class="menu-2">'.implode($items).'</ul>';

	}

}



/**
* Footer
*/

// Sort pages into pages and categories
$pages = array();
$categories = array();
foreach ($servant->sitemap()->pages() as $node) {
	if ($node->category()) {
		$categories[] = $node;
	} else {
		$pages[] = $node;
	}
}
unset($node);

// Top-level pages
$footerLists[0] = array(
	'<a href="'.$servant->paths()->root('domain').'">'.$servant->site()->name().'</a>',
	'<a href="'.$servant->paths()->endpoint('sitemap', 'domain', $page->tree()).'">Sitemap</a>'
);
foreach ($pages as $node) {
	$footerLists[0][] = '<a href="'.$node->endpoint('domain').'">'.$node->name().'</a>';
}
unset($pages, $node);

// Main categories and pages
$i = 1;
foreach ($categories as $category) {
	$footerLists[$i] = array();

	// Category title
	$footerLists[$i][] = '<a href="'.$servant->paths()->endpoint('site', 'domain', $category->id()).'">'.$category->name().'</a>';

	// Subpages
	$children = $category->children();
	foreach ($children as $child) {
		$footerLists[$i][] = '<a href="'.$child->endpoint('domain').'">'.$child->name().'</a>';
	}
	unset($child);

	$i++;
}

// Definition lists for pages
$footer = '';
foreach ($footerLists as $list) {
	$footer .= '<dl>
		<dt>'.$list[0].'</dt>';
		array_shift($list);
		foreach ($list as $item) {
			$footer .= '<dd>'.$item.'</dd>';
		}
	$footer .= '</dl>';
}




/**
* Full template
*/
$frame = '
<div class="frame">

	<div class="frame-header">
		<div class="frame-container">
			<h1><a href="'.$servant->paths()->root('domain').'">'.$servant->site()->name().'</a></h1>
			';



			// Menu if there are pages
			$headerMenu = $template->nest('list-toplevelpages');
			if ($headerMenu) {
				$frame .= '
				<div id="responsive-menu">
					<ul class="menu-1">
						'.$headerMenu.'
					</ul>
				</div>
				';
			}



			$frame .= '
			<div class="clear"></div>
		</div>
	</div>

	<div class="frame-body">
		<div class="frame-container">
			'.($menu ? '<div class="frame-sidebar">'.$menu.'</div>' : '').'
			<div class="frame-article">
				'.$template->content().'
			</div>
			<div class="clear"></div>
		</div>
	</div>

	<div class="frame-footer">
		<div class="frame-container">
			'.$footer.'
			<div class="clear"></div>
		</div>
	</div>
	<div class="clear"></div>

</div>';

// Output via the default template
echo $template->nest('html', $frame);
?>