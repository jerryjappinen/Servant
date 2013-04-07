<?php

/**
* Manipulate URLs to point to right places on the domain
*/

/**
* URLs in HTML
*/
function manipulateHtmlUrls ($string, $rootPath, $relativePath = '', $domainRootPath = null, $domainRelativePath = '') {

	// Fallback
	if (!is_string($domainRootPath)) {
		$domainRootPath = $rootPath;
		$domainRelativePath = $relativePath;
	}

	// src="/foo"
	$string = preg_replace('/(src)\s*=\s*[\"\'](?:\/)([^"\']*)[\"\']/U', '\\1'.'="'.$rootPath.'\\2"', $string);

	// src="foo", but not src="http://foo"
	$string = preg_replace('/(src)\s*=\s*[\"\'](?!\/|http:|https:|skype:|ftp:|#|mailto:|tel:)([^"\']*)[\"\']/U', '\\1'.'="'.$rootPath.$relativePath.'\\2"', $string);

	// href="/bar"
	$string = preg_replace('/(href)\s*=\s*[\"\'](?:\/)([^"\']*)[\"\']/U', '\\1'.'="'.$domainRootPath.'\\2"', $string);

	// href="bar", but not href="http://bar"
	$string = preg_replace('/(href)\s*=\s*[\"\'](?!\/|http:|https:|skype:|ftp:|#|mailto:|tel:)([^"\']*)[\"\']/U', '\\1'.'="'.$domainRootPath.$domainRelativePath.'\\2"', $string);

	return $string;
}

/**
* URLs in CSS
*/
function manipulateCssUrls ($string, $rootPath, $relativePath = '') {

	// Root-relative internal URLs
	$string = preg_replace('/url\(\s*("|\')?(?:\/)([^"\')]*)("|\')?\)/', 'url("'.$rootPath.'\\2")', $string);

	// Relative internal URLs
	$string = preg_replace('/url\(\s*("|\')?(?!\/|http:|https:|skype:|ftp:|#|mailto:|tel:)([^"\')]*)("|\')?\)/', 'url("'.$rootPath.$relativePath.'\\2")', $string);

	return $string;
}

?>