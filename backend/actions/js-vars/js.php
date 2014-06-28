<?php

// Paths
$constants['paths'] = array(
	'root' => prefix($servant->paths()->root(), '/'),
	'host' => $servant->paths()->host(),
	'actions' => unprefix($servant->paths()->endpoints(), $servant->paths()->root()),
	'assets' => $servant->paths()->assets(),
	'pages' => $servant->paths()->pages(),
	'page' => $page->endpoint(),
	'templates' => $servant->paths()->templates(),
);

// Page
$constants['page'] = array(
	'id' => $page->id(),
	'name' => $page->name(),
	'endpoint' => $page->endpoint(),
	'template' => $page->template(),
);

?>