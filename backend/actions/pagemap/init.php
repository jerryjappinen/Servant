<?php

// Root page
$sitemap = create_object(new ServantSitemap($servant))->init();
$root = $sitemap->root();

// Foo
$output = array();
foreach ($root->children() as $page) {
	$output[] = array(
		$page->categoryId(),
		$page->id(),
		($page->children() ? implode(', ', $page->listChildren('categoryId')) : ''),
	);
}

// $output = $sitemap->findPageTemplates($servant->paths()->pages('server'));
// $output = $sitemap->treatFileMap($output);
?>