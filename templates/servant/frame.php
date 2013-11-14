<?php

/**
* Submenu for read action
*/
$menu = '';
if ($action->isRead()) {

	// Generate menu
	$tempPages = $servant->pages()->level($page->tree(0));
	if ($tempPages) {
		$items = array();
		foreach ($tempPages as $tempPage) {

			// Name
			$name = $tempPage->name();

			// Children
			$submenu = '';
			$subItems = array();
			if ($tempPage->children() and $tempPage->level() > 1) {
				// Rename category
				$name = $tempPage->categoryName(1);

				// Include all pages on this level
				foreach ($tempPage->siblings() as $subPage) {

					// Child page HTML
					$url = $subPage->readPath('domain');
					$output = '<a href="'.$url.'">'.$subPage->name().'</a>';

					// Mark selected subpage
					$parents = $subPage->parentTree();
					$parent = end($parents);
					if ($page->tree(1) === $parent and $page->tree(2) === $subPage->id()) {
						$output = '<li class="selected"><strong>'.$output.'</strong>';
					} else {
						$output = '<li>'.$output;
					}

					// Close HTML
					$output .= '</li>';

					// Add item to submenu
					$subItems[] = $output;
					unset($output);
				}

				$submenu = '<ul class="menu-3">'.implode($subItems).'</ul>';
			}

			// Link HTML
			$url = $tempPage->readPath('domain');
			$output = '<a href="'.$url.'">'.$name.'</a>';

			// Mark selected page
			if ($page->tree(1) === $tempPage->id()) {
				$output = '<li class="selected"><strong>'.$output.'</strong>';
			} else {
				$output = '<li>'.$output;
			}

			// Close HTML
			$output .= ($submenu ? $submenu : '').'</li>';

			// Add item to menu
			$items[] = $output;
			unset($output);

		}

		// Menu structure
		$menu = '<ul class="menu-2">'.implode($items).'</ul>';

	}

}



/**
* Footer
*/

// Sort pages into pages and categories
$tempPages = array();
$categories = array();
foreach ($servant->pages()->map() as $key => $value) {
	if (is_array($value)) {
		$categories[] = $key;
	} else {
		$tempPages[] = $value;
	}
}

// Top-level pages
$footerLists[0] = array(
	'<a href="'.$servant->paths()->root('domain').'">'.$servant->site()->name().'</a>',
	'<a href="'.$servant->paths()->endpoint('sitemap', 'domain', $page->tree()).'">Sitemap</a>'
);
foreach ($tempPages as $tempPage) {
	$footerLists[0][] = '<a href="'.$tempPage->readPath('domain').'">'.$tempPage->categoryName(0).'</a>';
}
unset($tempPages, $tempPage);

// Main categories and pages
$i = 1;
foreach ($categories as $categoryId) {
	$footerLists[$i] = array();

	// Category title
	$tempPages = $servant->pages()->level($categoryId);
	$footerLists[$i][] = '<a href="'.$servant->paths()->endpoint('site', 'domain', $categoryId).'">'.$tempPages[0]->categoryName().'</a>';

	// Subpages
	foreach ($tempPages as $tempPage) {
		$footerLists[$i][] = '<a href="'.$tempPage->readPath('domain').'/">'.$tempPage->categoryName(1).'</a>';
	}
	unset($tempPage);

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