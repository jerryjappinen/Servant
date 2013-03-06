<?php

// Prefix URLs with correct paths
function manipulateHtmlUrls ($string, $rootPath, $relativePath = '') {

    // Root-relative internal URLs
    $string = preg_replace('/(src|href)\s*=\s*[\"\'](?:\/)([^"\']*)[\"\']/U', '\\1'.'="'.$rootPath.'\\2"', $string);

    // Relative internal URLs
    $string = preg_replace('/(src|href)\s*=\s*[\"\'](?!\/|http:|https:|skype:|ftp:|#|mailto:|tel:)([^"\']*)[\"\']/U', '\\1'.'="'.$rootPath.$relativePath.'\\2"', $string);

    return $string;
}

// Prefix URLs with correct paths
function manipulateCssUrls ($string, $rootPath, $relativePath = '') {

    // Root-relative internal URLs
    $string = preg_replace('/url\(\s*("|\')?(?:\/)([^"\')]*)("|\')?\)/', 'url("'.$rootPath.'\\2")', $string);

    // Relative internal URLs
    $string = preg_replace('/url\(\s*("|\')?(?!\/|http:|https:|skype:|ftp:|#|mailto:|tel:)([^"\')]*)("|\')?\)/', 'url("'.$rootPath.$relativePath.'\\2")', $string);

    return $string;
}

?>