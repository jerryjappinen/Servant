<?php

/**
* Manipulate URLs to point to right places on the domain
*
* FLAG
*   - Protocols should not be hardcoded
*   - Should support linking to an action
*/

/**
* URLs in HTML
*/
function manipulateHtmlUrls ($string, $srcRootPath, $srcRelativePath = '', $hrefRootPath = null, $hrefRelativePath = '', $actionsPath = null) {

	// Fallbacks
	if (!is_string($hrefRootPath)) {
		$hrefRootPath = $srcRootPath;
		$hrefRelativePath = $srcRelativePath;
	}
	if (!is_string($actionsPath)) {
		$actionsPath = $hrefRootPath;
	}

	// src="/foo"
	$string = preg_replace('/(src)\s*=\s*[\"\'](?:\/)(?!\/)([^"\']*)[\"\']/U', '\\1'.'="'.$srcRootPath.'\\2"', $string);

	// src="//foo" points to actions
	$string = preg_replace('/(src)\s*=\s*[\"\'](?:\/\/)([^"\']*)[\"\']/U', '\\1'.'="'.$actionsPath.'\\2"', $string);

	// src="foo", but not src="http://foo"
	$string = preg_replace('/(src)\s*=\s*[\"\'](?!\/|http:|https:|skype:|ftp:|#|mailto:|tel:)([^"\']*)[\"\']/U', '\\1'.'="'.$srcRootPath.$srcRelativePath.'\\2"', $string);

	// href="/bar"
	$string = preg_replace('/(href)\s*=\s*[\"\'](?:\/)(?!\/)([^"\']*)[\"\']/U', '\\1'.'="'.$hrefRootPath.'\\2"', $string);

	// href="//bar" points to actions
	$string = preg_replace('/(href)\s*=\s*[\"\'](?:\/\/)([^"\']*)[\"\']/U', '\\1'.'="'.$actionsPath.'\\2"', $string);

	// href="bar", but not href="http://bar"
	$string = preg_replace('/(href)\s*=\s*[\"\'](?!\/|http:|https:|skype:|ftp:|#|mailto:|tel:)([^"\']*)[\"\']/U', '\\1'.'="'.$hrefRootPath.$hrefRelativePath.'\\2"', $string);

	return $string;
}

/**
* URLs in CSS
*/
function manipulateCssUrls ($string, $rootPath, $relativePath = '', $actionsPath = null) {

	// Fallbacks
	if (!is_string($actionsPath)) {
		$actionsPath = $hrefRootPath;
	}

	// url(/foo) - root-relative internal URLs
	$string = preg_replace('/url\(\s*("|\')?(?:\/)(?!\/)([^"\')]*)("|\')?\)/', 'url("'.$rootPath.'\\2")', $string);

	// url(/foo) - URLs to actions
	$string = preg_replace('/url\(\s*("|\')?(?:\/\/)([^"\')]*)("|\')?\)/', 'url("'.$actionsPath.'\\2")', $string);

	// url(foo) - relative internal URLs
	$string = preg_replace('/url\(\s*("|\')?(?!\/|http:|https:|skype:|ftp:|#|mailto:|tel:)([^"\')]*)("|\')?\)/', 'url("'.$rootPath.$relativePath.'\\2")', $string);

	return $string;
}

?>