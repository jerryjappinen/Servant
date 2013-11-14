<?php

// Root page
$sitemap = $servant->create()->sitemap();
$root = $sitemap->root();
$children = $root->children();

// Foo
// $output = array();
// foreach ($root->children() as $page) {
// 	$output[] = array(
// 		$page->categoryId(),
// 		$page->id(),
// 		($page->children() ? implode(', ', $page->listChildren('categoryId')) : ''),
// 	);
// }

$output = $sitemap->dump();
// $output = $servant->paths()->pages('server');
// $output = $sitemap->findPageTemplates($output);
// $output = $sitemap->treatFileMap($output);
?>