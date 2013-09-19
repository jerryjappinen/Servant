<?php
$path = dirname($servant->article()->path('server'));
$images = rglob_files($path, 'jpg', 'png');

// Print list of images
foreach ($images as $key => $value) {

	// Treat URL for linking
	$url = $servant->format()->path($value, 'plain', 'server');
	$url = unprefix($url, $servant->paths()->sites());

	echo '
	<h4>'.$servant->format()->title(pathinfo($value, PATHINFO_FILENAME)).'</h4>
	<p><img src="'.basename($value).'"></p>
	';
}

?>
