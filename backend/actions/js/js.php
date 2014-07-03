<?php

// Paths
$constants['paths'] = array(

	// Parts
	'root' => prefix($servant->paths()->root('domain'), '/'),
	'host' => unsuffix($servant->paths()->host(), '/'),

	// URLs
	'actions' => $servant->paths()->endpoints('domain'),
	'assets' => $servant->paths()->assets('domain'),
	'pages' => $servant->paths()->pages('domain'),
	'templates' => $servant->paths()->templates('domain'),

);

?>