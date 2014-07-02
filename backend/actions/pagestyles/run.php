<?php

/**
* FLAG
*   - Not very elegant or dynamic
*/

// Find out what preprocessor formats are supported
$allowedFormats = array();
$temp = $servant->constants()->formats('stylesheets');
unset($temp['css']);
foreach ($temp as $type => $extensions) {
	foreach ($extensions as $extension) {
		$allowedFormats[$extension] = $type;
	}
}
unset($temp, $type, $extensions, $extension);

// All stylesheets go here
$siteStyles = array('format' => false, 'content' => '');
$pageStyles = array('format' => false, 'content' => '');

// We need this for URL manipulations
$actionsPath = $servant->paths()->root('domain');



/**
* Site-wide styles
*
* If SCSS or LESS is used, the first such file determines the type used for the whole set. These cannot be mixed within one set.
*
* NOTE
*   - We don't output site-wide styles, we just need to have it there to preprocess page styles
*/
foreach ($servant->assets()->stylesheets('plain') as $path) {

	// Special format is used
	$extension = pathinfo($path, PATHINFO_EXTENSION);
	if (array_key_exists($extension, $allowedFormats)) {

		// Set's format has not been selected yet, we'll do it now
		if (!$siteStyles['format']) {
			$siteStyles['format'] = $allowedFormats[$extension];

		// Mixing preprocessor formats will fail
		} else if ($siteStyles['format'] !== $allowedFormats[$extension]) {
			fail('CSS preprocessor formats cannot be mixed in assets');
		}

	}
	unset($extension);



	// Root is asset directory root
	$rootUrl = $servant->paths()->assets('domain');

	// We can parse relative path
	$relativeUrl = substr((dirname($path).'/'), strlen($servant->paths()->assets('plain')));

	// Get CSS file contents with URLs replaced
	$siteStyles['content'] .= $urlManipulator->cssUrls(file_get_contents($servant->paths()->format($path, 'server')), $rootUrl, $relativeUrl, $actionsPath);
}



/**
* Page-specific style files
*/

// Select root by default, page node if pointer given
$page = $servant->sitemap()->root();
if (count($input->pointer())) {
	$page = $servant->sitemap()->select($input->pointer())->page();
}

foreach ($page->stylesheets('plain') as $path) {

	// A preprocessor format is used
	$extension = pathinfo($path, PATHINFO_EXTENSION);
	if (array_key_exists($extension, $allowedFormats)) {

		// Set's format has not been selected yet, we'll do it now
		if (!$pageStyles['format']) {
			$pageStyles['format'] = $allowedFormats[$extension];

		// Mixing specia formats will fail
		} else if ($pageStyles['format'] !== $allowedFormats[$extension]) {
			fail('CSS preprocessor formats cannot be mixed in page styles');
		}

	}
	unset($extension);



	// Root is the root pages directory
	$rootUrl = $servant->paths()->pages('domain');

	// We can parse relative path
	$relativeUrl = substr((dirname($path).'/'), strlen($servant->paths()->pages('plain')));

	// Get CSS file contents with URLs replaced
	$pageStyles['content'] .= $urlManipulator->cssUrls(file_get_contents($servant->paths()->format($path, 'server')), $rootUrl, $relativeUrl, $actionsPath);
}



/**
* Output
*/

// If site and page styles use the same superset format, we parse them as one so variables from site can be used in page styles
$parseAsOne = false;
if ($siteStyles['format'] and $siteStyles['format'] === $pageStyles['format']) {
	$parseAsOne = true;

	// We merge site stylesheets into page styles, and remove them after parsing
	$pageStyles['content'] = $siteStyles['content'].$pageStyles['content'];
}

// Parse sets
$output = array();
foreach (array($siteStyles, $pageStyles) as $stylesheetSet) {

	// Parse LESS
	if ($stylesheetSet['format'] === 'less') {
		$servant->utilities()->load('less');
		$parser = new lessc();

		// Don't compress in debug mode
		if (!$servant->debug()) {
			$parser->setFormatter('compressed');
		}

		$output[] = $parser->parse($stylesheetSet['content']);

	// Parse SCSS
	} else if ($stylesheetSet['format'] === 'scss') {
		$servant->utilities()->load('scss');
		$parser = new scssc();

		// Don't compress in debug mode
		if (!$servant->debug()) {
			$parser->setFormatter('scss_formatter_compressed');
		}

		$output[] = $parser->compile($stylesheetSet['content']);

	// Raw CSS, apparently
	} else {
		$output[] = $stylesheetSet['content'];
	}

}

// Remove prepended sitestyles from output
if ($parseAsOne) {
	$output = substr($output[1], strlen($output[0]));
} else {
	$output = $output[1];
}



// We're done
$action->output(trim($output));

?>