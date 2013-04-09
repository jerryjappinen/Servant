<?php

// Require utilities
$servant->utilities()->load('urls', 'less');

// All stylesheets for site
$output = '';



// Merge all stylesheets from theme
foreach ($servant->theme()->stylesheets('plain') as $path) {

	// Root is theme directory root
	$rootUrl = $this->servant()->theme()->path('domain');

	// We can parse relative path from that
	$relativeUrl = substr((pathinfo($path, PATHINFO_DIRNAME).'/'), strlen($this->servant()->theme()->path('plain')));

	// Get CSS file contents with URLs replaced
	$output .= manipulateCSSUrls(file_get_contents($this->servant()->format()->path($path, 'server')), $rootUrl, $relativeUrl);
}



// Merge stylesheets from site
foreach ($servant->site()->article()->stylesheets('plain') as $path) {

	// Root is site directory root
	$rootUrl = $this->servant()->site()->path('domain');

	// We can parse relative path from that
	$relativeUrl = substr((pathinfo($path, PATHINFO_DIRNAME).'/'), strlen($this->servant()->site()->path('plain')));

	// Get CSS file contents with URLs replaced
	$output .= manipulateCSSUrls(file_get_contents($this->servant()->format()->path($path, 'server')), $rootUrl, $relativeUrl);
}



// Parse LESS
$output = trim($output);
if (!empty($output)) {
	$less = new lessc();

	// Compress
	// FLAG this is a hack
	if ($this->servant()->settings()->cache('server')) {
		$less->setFormatter('compressed');
	}

	$output = $less->parse($output);
}



// Output CSS
$servant->action()->contentType('css')->output($output);

?>