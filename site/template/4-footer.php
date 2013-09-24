<?php
$footerContent = '';



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



// Pages & generic stuff
$footerContent .= '<dl><dt><a href="'.$servant->paths()->root('domain').'">'.$servant->site()->name().'</a></dt><dd><a href="'.$servant->paths()->userAction('sitemap', 'domain', $servant->page()->tree()).'">Sitemap</a></dd>
';



// Create footer links for top-level pages
foreach ($pages as $page) {
	$footerContent .= '<dd><a href="'.$servant->paths()->userAction('read', 'domain', $page->tree()).'">'.$page->categoryName(0).'</a></dd>';
}
unset($page);
$footerContent .= '</dl>';



// Create footer links for categories
foreach ($categories as $categoryId) {

	// Category title
	$footerContent .= '<dl><dt><a href="'.$servant->paths()->userAction('read', 'domain', $categoryId).'">'.$servant->format()->pageName($categoryId).'</a></dt>';

	// Subpages
	foreach ($servant->pages()->level($categoryId) as $page) {
		$footerContent .= '<dd><a href="'.$servant->paths()->userAction('read', 'domain', $page->tree()).'/">'.$page->categoryName(1).'</a></dd>';
	}

	$footerContent .= '</dl>';
}



// Footer
echo '
<div class="frame-footer">
	<div class="frame-container">
		'.$footerContent.'
		<div class="clear"></div>
	</div>
</div>
<div class="clear"></div>
';

?>