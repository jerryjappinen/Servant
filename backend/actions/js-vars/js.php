<?php
$page = $servant->sitemap()->select($input->pointer())->page();
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



// Page
$js['page'] = array(
	'id' => $page->id(),
	'name' => $page->name(),
	'endpoint' => $page->endpoint(),
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