<?php
$page = $servant->sitemap()->select($input->fetch('page', 'queue', array()));
$js = array();

// Paths
$js['paths'] = array(
	'root' => prefix($servant->paths()->root(), '/'),
	'host' => $servant->paths()->host(),
	'actions' => unprefix($servant->paths()->endpoints(), $servant->paths()->root()),
	'assets' => $servant->paths()->assets(),
	'pages' => $servant->paths()->pages(),
	'page' => $page->endpoint(),
	'templates' => $servant->paths()->templates(),
);



// Settings
$js['site'] = array(
	'name' => $servant->site()->name(),
	'description' => $servant->site()->description(),
	'icon' => $servant->site()->icon(),
	'splashImage' => $servant->site()->splashImage(),
	'template' => $servant->site()->template(),
);



// Page
$js['page'] = array(
	'name' => $page->name(),
	'id' => $page->id(),
	'pointer' => $page->pointer(),
	'template' => $page->template(),
);



// Full output
$js = '/*
 * Environment variables for JS component
 *
 * Exposes Servant-related configuration to client-side
 */
var servantConstants = function () {
	var constants = '.str_replace('\\/', '/', json_encode($js)).';
	return constants;
};';
?>