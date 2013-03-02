<?php

// Paths
$paths = array(
	'root' 					=> $servant->paths()->root(),
	'root (server)' 		=> $servant->paths()->root('server'),
	'root (domain)' 		=> $servant->paths()->root('domain'),
);

// Settings
$settings = array(
	'templateLanguages' => $servant->settings()->templateLanguages()
);

// Output
echo '<pre>'.
// dump($paths).
dump($settings).
'</pre>';

?>