<?php

/**
* Site-wide styles
*
* If SCSS or LESS is used, the first such file determines the type used for the whole set. These cannot be mixed within one set.
*/
$allowedFormats = array();
$temp = $servant->settings()->formats('stylesheets');
unset($temp['css']);
foreach ($temp as $type => $extensions) {
	foreach ($extensions as $extension) {
		$allowedFormats[$extension] = $type;
	}
}
unset($temp, $type, $extensions, $extension);



/**
* URL manipulation
*/
$actionsPath = $servant->paths()->root('domain');
$assetsRootUrl = $servant->paths()->assets('domain');
$urlManipulator = new UrlManipulator();



/**
* Go through files
*/
$styles = array('format' => false, 'content' => '',);
foreach ($servant->site()->stylesheets('plain') as $path) {

	// Special format is used
	$extension = pathinfo($path, PATHINFO_EXTENSION);
	if (array_key_exists($extension, $allowedFormats)) {

		// Set's format has not been selected yet, we'll do it now
		if (!$styles['format']) {
			$styles['format'] = $allowedFormats[$extension];

		// Mixing preprocessor formats will fail
		} else if ($styles['format'] !== $allowedFormats[$extension]) {
			fail('CSS preprocessor formats cannot be mixed in assets');
		}

	}
	unset($extension);

	// We can parse relative path
	$relativeUrl = substr((dirname($path).'/'), strlen($servant->paths()->assets('plain')));

	// Get CSS file contents with URLs replaced
	$styles['content'] .= $urlManipulator->cssUrls(file_get_contents($servant->format()->path($path, 'server')), $assetsRootUrl, $relativeUrl, $actionsPath);

}



/**
* Output
*/
$output = '';
if ($styles['format']) {
	$methodName = $styles['format'].'ToCss';

	// Parse if possible
	if (method_exists($servant->parse(), $methodName)) {
		$output .= $servant->parse()->$methodName($styles['content']);
	} else {
		fail(strtoupper($styles['format']).' language is not supported.');
	}

// Raw CSS
} else {
	$output .= $styles['content'];
}
$action->output(trim($output));

?>