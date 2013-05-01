<?php

// All stylesheets for site go here
$stylesheetSets = array(
	array('language' => false, 'content' => ''),
	array('language' => false, 'content' => ''),
);

// We need this for URL manipulations
$actionsPath = $servant->paths()->root('domain').$servant->site()->id().'/';



/**
* Theme's style files
*/
foreach ($servant->theme()->stylesheets('plain') as $path) {

	// Detect format (based on first superset file)
	// FLAG shouldn't be using file extension directly
	// FLAG hardcoding extensions
	$language = pathinfo($path, PATHINFO_EXTENSION);
	if (in_array($language, array('scss', 'less'))) {

		// Format is now selected for theme package
		if (!$stylesheetSets[0]['language']) {
			$stylesheetSets[0]['language'] = $language;

		// Fail if supersets are mixed
		} else if ($stylesheetSets[0]['language'] !== $language) {
			fail('SCSS and LESS cannot be mixed in a theme package');
		}
	}
	unset($language);



	// Root is theme directory root
	$rootUrl = $servant->theme()->path('domain');

	// We can parse relative path
	$relativeUrl = substr((dirname($path).'/'), strlen($servant->theme()->path('plain')));

	// Get CSS file contents with URLs replaced
	$stylesheetSets[0]['content'] .= manipulateCSSUrls(file_get_contents($servant->format()->path($path, 'server')), $rootUrl, $relativeUrl, $actionsPath);
}



/**
* Site's style files
*/
foreach ($servant->site()->article()->stylesheets('plain') as $path) {

	// Detect format (based on first superset file)
	// FLAG shouldn't be using file extension directly
	// FLAG hardcoding extensions
	$language = pathinfo($path, PATHINFO_EXTENSION);
	if (in_array($language, array('scss', 'less'))) {

		// Format is now selected for site's stylesheets
		if (!$stylesheetSets[1]['language']) {
			$stylesheetSets[1]['language'] = $language;

		// Fail if supersets are mixed
		} else if ($stylesheetSets[1]['language'] !== $language) {
			fail('SCSS and LESS cannot be mixed in site\'s stylesheets');
		}
	}
	unset($language);

	// Root is site directory root
	$rootUrl = $servant->site()->path('domain');

	// We can parse relative path
	$relativeUrl = substr((dirname($path).'/'), strlen($servant->site()->path('plain')));

	// Get CSS file contents with URLs replaced
	$stylesheetSets[1]['content'] .= manipulateCSSUrls(file_get_contents($servant->format()->path($path, 'server')), $rootUrl, $relativeUrl, $actionsPath);
}



/**
* Output
*/

// Theme and site styles use the same superset language; parse as one
// FLAG not very elegant or dynamic
if ($stylesheetSets[0]['language'] and $stylesheetSets[0]['language'] === $stylesheetSets[1]['language']) {
	$stylesheetSets[0]['content'] = $stylesheetSets[0]['content'].$stylesheetSets[1]['content'];
	unset($stylesheetSets[1]);
}

// Go through each set
$output = '';
foreach ($stylesheetSets as $stylesheetSet) {

	// Superset language is used
	if ($stylesheetSet['language']) {
		$methodName = $stylesheetSet['language'].'ToCss';
		$output .= $servant->parse()->$methodName($stylesheetSet['content']);

	// Raw CSS
	} else {
		$output .= $stylesheetSet['content'];
	}

}



// We're done
$servant->action()->contentType('css')->output(trim($output));

?>