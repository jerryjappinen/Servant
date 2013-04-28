<?php

// Require utilities
$servant->utilities()->load('urls', 'less');

// All stylesheets for site
$output = '';



// We need this for URL manipulations
$actionsPath = $servant->paths()->root('domain').$servant->site()->id().'/';

// Merge all stylesheets from theme
foreach ($servant->theme()->stylesheets('plain') as $path) {

	// Root is theme directory root
	$rootUrl = $servant->theme()->path('domain');

	// We can parse relative path from that
	$relativeUrl = substr((dirname($path).'/'), strlen($servant->theme()->path('plain')));

	// Get CSS file contents with URLs replaced
	$output .= manipulateCSSUrls(file_get_contents($servant->format()->path($path, 'server')), $rootUrl, $relativeUrl, $actionsPath);
}



// Merge stylesheets from site
foreach ($servant->site()->article()->stylesheets('plain') as $path) {

	// Root is site directory root
	$rootUrl = $servant->site()->path('domain');

	// We can parse relative path from that
	$relativeUrl = substr((dirname($path).'/'), strlen($servant->site()->path('plain')));

	// Get CSS file contents with URLs replaced
	$output .= manipulateCSSUrls(file_get_contents($servant->format()->path($path, 'server')), $rootUrl, $relativeUrl, $actionsPath);
}



// Parse LESS
$output = trim($output);
if (!empty($output)) {
	$less = new lessc();

	// Compress
	// FLAG this is a hack
	if ($servant->settings()->cache('server')) {
		$less->setFormatter('compressed');
	}

	$output = $less->parse($output);
}



// Output CSS
$servant->action()->contentType('css')->output($output);

?>