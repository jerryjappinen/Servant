<?php

// Select page
$page = $servant->sitemap()->select($input->pointer());



/**
* URL manipulation for page content
*/

// Root path for src attributes
$srcUrl = $servant->paths()->pages('domain');

// Root path for hrefs
// FLAG use servant->paths->action
$hrefUrl = $servant->paths()->root('domain').$servant->constants()->actions('site').'/';

// Relative location for SRC urls
$dirname = suffix(dirname($page->path('plain')), '/');
$relativeSrcUrl = unprefix($dirname, $servant->paths()->pages('plain'), true);
if (!empty($relativeSrcUrl)) {
	$relativeSrcUrl = suffix($relativeSrcUrl, '/');
}

// Relative location for HREF urls
$tree = $page->tree();
array_pop($tree);
$relativeHrefUrl = implode('/', $tree);
if (!empty($relativeHrefUrl)) {
	$relativeHrefUrl .= '/';
}

// Base URL to point to actions on the domain
$actionsUrl = $servant->paths()->root('domain');



// Set output
$servant->utilities()->load('urlmanipulator');
$manipulate = new UrlManipulator();
$action->contentType('html')->output($manipulate->htmlUrls($page->output(), $srcUrl, $relativeSrcUrl, $hrefUrl, $relativeHrefUrl, $actionsUrl));

?>