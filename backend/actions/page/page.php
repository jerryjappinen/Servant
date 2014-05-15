<?php

// Select page
$page = $servant->sitemap()->select($input->pointer())->page();



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
$pointer = $page->pointer();
array_pop($pointer);
$relativeHrefUrl = implode('/', $pointer);
if (!empty($relativeHrefUrl)) {
	$relativeHrefUrl .= '/';
}

// Base URL to point to actions on the domain
$actionsUrl = $servant->paths()->root('domain');



// Run page scripts with URL parameters, excluding page pointers
$parameters = $input->pointer();
$tree = $page->pointer();
$i = 0;
for ($i = 0; $i < count($tree); $i++) { 
	if (!isset($parameters[$i]) || $tree[$i] !== $parameters[$i]) {
		break;
	}	
}
$parameters = array_slice($parameters, $i);

$output = $page->output($parameters);



// Set manipulated output
$servant->utilities()->load('urlmanipulator');
$manipulate = new UrlManipulator();
$action->contentType('html')->output($manipulate->htmlUrls($output, $srcUrl, $relativeSrcUrl, $hrefUrl, $relativeHrefUrl, $actionsUrl));

?>