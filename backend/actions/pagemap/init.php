<?php

$output = $servant->sitemap()->select('technical-docs', 'template-formats', 'html')->tree();

$parents = $page->parents();
$parent = $parents[1];
$output = $parent->name();

?>