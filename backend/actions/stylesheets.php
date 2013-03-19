<?php

// Merge all stylesheets
$output = '';
foreach ($servant->theme()->stylesheets('plain') as $path) {

	// Manipulate relative URLs

	// Root is theme directory root
	$rootUrl = $this->servant()->theme()->path('domain');
	if (!$rootUrl) {
		$rootUrl = $this->servant()->paths()->themes('domain');
	}

	// We can parse relative path from that
	$relativeUrl = substr((pathinfo($path, PATHINFO_DIRNAME).'/'), strlen($this->servant()->theme()->path('plain')));

	// Get CSS file contents with URLs replaced
	$output .= manipulateCSSUrls(file_get_contents($this->servant()->format()->path($path, 'server')), $rootUrl, $relativeUrl);

}

// Parse LESS
$output = trim($output);
if (!empty($output)) {
	$less = new lessc();

	// Compress when not in debug mode
	if ($servant->response()->browserCacheTime() > 0) {
		$less->setFormatter('compressed');
	}

	$output = $less->parse($output);
}

// Output CSS
$servant->action()->contentType('css')->output($output);

?>