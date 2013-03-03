<?php

$output = '
<!DOCTYPE html>
<html lang="en">
	<head>

		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<style type="text/css">@-ms-viewport{width: device-width;}</style>

		<title>Debugging '.$servant->site()->name().' in Servant</title>
		';

		// Use a favicon if there is one
		foreach (rglob_files($servant->theme()->path('server'), 'ico') as $path) {
			$output .= '<link rel="shortcut icon" href="'.$servant->format()->path($path, 'domain', 'server').'" type="image/x-icon">';
			break;
		}

		// Stylesheets
		foreach ($servant->theme()->stylesheetFiles('domain') as $path) {
			$output .= '<link rel="stylesheet" href="'.$path.'" media="screen">';
		}

		$output .= '
	</head>

	<body class="'.implode(' ', $servant->site()->selected()).'">

		<h2>This is some dark magic shit right here</h2>
		';

		$servant->debug(
			$servant->available()->template('debug'),
			$servant->available()->template('debug')
		);

		$output .= '
	</body>
</html>
';

echo $output;
?>