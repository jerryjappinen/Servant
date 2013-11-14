<?php
$js = array();

// Paths
$js['paths'] = array(
	'root' => prefix($servant->paths()->root(), '/'),
	'host' => $servant->paths()->host(),
	'actions' => $servant->paths()->endpoints(),
	'theme' => $servant->paths()->theme(),
	'pages' => $servant->paths()->pages(),
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



// Full output
$js = '/*
 * Servant JS component
 *
 * Exposes Servant-related configuration to client-side
 */
(function(window, undefined ) {
	var system = {'.str_replace('\\/', '/', json_encode($js)).'};
	window.system = system;
})(window);';
?>