
<!DOCTYPE html>
<html lang="<?= $servant->site()->language() ?>">
	<head>

		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<style type="text/css">
			@-ms-viewport{width: device-width;}
			@-o-viewport{width: device-width;}
			@viewport{width: device-width;}
		</style>

		<title><?= $servant->site()->name() ?></title>
		<meta name="application-name" content="<?= $servant->site()->name() ?>">

		<?php
		// Custom web site icon
		$icon = $servant->site()->icon('domain');
		if (empty($icon)) {
			$icon = $servant->theme()->icon('domain');
		}
		if (!empty($icon)) {
			$extension = pathinfo($icon, PATHINFO_EXTENSION);

			// .ico for browsers
			if ($extension === 'ico') {
				echo '<link rel="shortcut icon" href="'.$icon.'" type="image/x-icon">';

			// Images for browsers, iOS, Windows 8
			} else {
				echo '
				<link rel="icon" href="'.$icon.'" type="'.$servant->settings()->contentTypes($extension).'">
				<link rel="apple-touch-icon-precomposed" href="'.$icon.'" />
				<meta name="msapplication-TileImage" content="'.$icon.'"/>';
				// echo '<meta name="msapplication-TileColor" content="#d83434"/>';
			}

			unset($extension);
		}
		unset($icon);
		?>


		<?php
		// Stylesheets, possibly page-specific
		// FLAG I really shouldn't hardcode the name of read action...
		$temp = $servant->paths()->root('domain').'stylesheets/';
		if ($servant->action()->id() === 'read') {
			$temp .= implode('/', $servant->page()->tree()).'/';
		}
		?>
		<link rel="stylesheet" href="<?= $temp ?>" media="screen">

	</head>


<?php
// Create classes for body
$i = 1;
$classes = array();
$tree = $servant->page()->tree();
foreach ($tree as $value) {
	$classes[] = 'page-'.implode('-', array_slice($tree, 0, $i));
	$i++;
}
unset($tree, $i);
?>

<body class="level-<?= count($servant->page()->tree())?> index-<?= $servant->page()->index()?> <?= implode(' ', $classes) ?>">
