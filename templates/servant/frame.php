<?php



/**
* Header
*/
$header = '<h1><a href="'.$servant->paths()->root('domain').'">'.$servant->site()->name().'</a></h1>';

// Level 1 menu
$pages = $servant->pages()->level();
if ($pages) {
	$header .= '<div id="responsive-menu"><ul class="menu-1">';
	foreach ($pages as $page) {

		// Link in a list item, possibly selected
		$link = '<a href="'.$page->readPath('domain').'">'.$page->categoryName(0).'</a>';
		$header .= '<li>'.($page->tree(0) === $servant->pages()->current()->tree(0) ? '<strong>'.$link.'</strong>' : $link).'</li>';

	}
	$header .= '</ul></div>';
}
unset($pages, $link, $page);



/**
* Submenu for read action
*/
$menu = '';
if ($servant->action()->isRead()) {

	// Generate menu
	$pages = $servant->pages()->level($servant->page()->tree(0));
	if ($pages) {
		$items = array();
		foreach ($pages as $page) {

			// Name
			$name = $page->name();

			// Children
			$submenu = '';
			$subItems = array();
			if ($page->children() and $page->level() > 1) {
				// Rename category
				$name = $page->categoryName(1);

				// Include all pages on this level
				foreach ($page->siblings() as $subPage) {

					// Child page HTML
					$url = $subPage->readPath('domain');
					$output = '<a href="'.$url.'">'.$subPage->name().'</a>';

					// Mark selected subpage
					$parents = $subPage->parentTree();
					$parent = end($parents);
					if ($servant->page()->tree(1) === $parent and $servant->page()->tree(2) === $subPage->id()) {
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
			$url = $page->readPath('domain');
			$output = '<a href="'.$url.'">'.$name.'</a>';

			// Mark selected page
			if ($servant->page()->tree(1) === $page->id()) {
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
$pages = array();
$categories = array();
foreach ($servant->pages()->map() as $key => $value) {
	if (is_array($value)) {
		$categories[] = $key;
	} else {
		$pages[] = $value;
	}
}

// Top-level pages
$footerLists[0] = array(
	'<a href="'.$servant->paths()->root('domain').'">'.$servant->site()->name().'</a>',
	'<a href="'.$servant->paths()->userAction('sitemap', 'domain', $servant->page()->tree()).'">Sitemap</a>'
);
foreach ($pages as $page) {
	$footerLists[0][] = '<a href="'.$page->readPath('domain').'">'.$page->categoryName(0).'</a>';
}
unset($page);

// Main categories and pages
$i = 1;
foreach ($categories as $categoryId) {
	$footerLists[$i] = array();

	// Category title
	$footerLists[$i][] = '<a href="'.$servant->paths()->userAction('site', 'domain', $categoryId).'">'.$servant->format()->pageName($categoryId).'</a>';

	// Subpages
	foreach ($servant->pages()->level($categoryId) as $page) {
		$footerLists[$i][] = '<a href="'.$page->readPath('domain').'/">'.$page->categoryName(1).'</a>';
	}
	unset($page);

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
			'.$header.'
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
echo $servant->template('default', $frame)->output();
?>