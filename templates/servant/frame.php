<?php

/**
* Footer composition
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

// Menus
$mainmenu = $template->nest('list-toplevelpages');
$submenu = $action->isRead() ? $template->nest('list-submenu') : '';

$frame = '
<div class="frame">

	<div class="frame-header">
		<div class="frame-container">
			<h1><a href="'.$servant->paths()->root('domain').'">'.$servant->site()->name().'</a></h1>
			'.($mainmenu ? '<div id="responsive-menu">'.$mainmenu.'</div>' : '').'
			<div class="clear"></div>
		</div>
	</div>

	<div class="frame-body">
		<div class="frame-container">
			'.($submenu ? '<div class="frame-sidebar">'.$submenu.'</div>' : '').'
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