<?php

echo '
<!DOCTYPE html>
<html>
	<head>

		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<style type="text/css">@-ms-viewport{width: device-width;}</style>

		<title>'.$servant->site()->name().'</title>
		';

		// Use a favicon if there is one
		if (is_dir($servant->theme()->path('server'))) {
			foreach (rglob_files($servant->theme()->path('server'), 'ico') as $path) {
				echo '<link rel="shortcut icon" href="'.$servant->format()->path($path, 'domain', 'server').'" type="image/x-icon">';
				break;
			}
		}

		// Stylesheets
		echo '<link rel="stylesheet" href="'.$servant->paths()->root('domain').$servant->site()->id().'/stylesheets/'.'" media="screen">';

		echo '
	</head>
';

?>