
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

		<?php

		// Page title
		$title = (!$servant->page()->isHome() ? $servant->page()->name().' &ndash; ' : '').$servant->site()->name();
		echo '<title>'.$title.'</title><meta property="og:title" content="'.$title.'">';
		unset($title);

		// Site name
		echo '<meta name="application-name" content="'.$servant->site()->name().'"><meta property="og:site_name" content="'.$servant->site()->name().'">';

		// Description
		$description = trim_text($servant->site()->description(), true);
		if ($description) {
			echo '<meta name="description" content="'.$description.'"><meta property="og:description" content="'.$description.'">';
		}
		unset($description);

		// Other Open Graph stuff
		echo '<meta property="og:type" content="'.($servant->page()->isHome() ? 'website' : 'article').'"><meta property="og:url" content="'.$servant->paths()->root('url').'">';



		// Splash image
		$splashImage = $servant->site()->splashImage('url');
		if ($splashImage) {
			echo '<meta property="og:image" content="'.$splashImage.'"><meta name="msapplication-TileImage" content="'.$splashImage.'"/>';
		}

		// Icon
		$icon = $servant->site()->icon('domain');
		if ($icon) {
			$extension = pathinfo($icon, PATHINFO_EXTENSION);

			// .ico for browsers
			if ($extension === 'ico') {
				echo '<link rel="shortcut icon" href="'.$icon.'" type="image/x-icon">';

			// Image icons for browsers and various platforms
			} else {
				echo '<link rel="icon" href="'.$icon.'" type="'.$servant->settings()->contentTypes($extension).'"><link rel="apple-touch-icon-precomposed" href="'.$icon.'" />';
				echo ($splashImage ? '' : '<meta name="msapplication-TileImage" content="'.$icon.'"/>');
			}

			unset($extension);

		}
		unset($splashImage, $icon);



		// Stylesheets, possibly page-specific
		$tree = array();
		if ($servant->action()->isRead()) {
			$tree = $servant->page()->tree();
		}
		echo '<link rel="stylesheet" href="'.$servant->paths()->userAction('stylesheets', 'domain', $tree).'" media="screen">';
		unset($tree);
		?>

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
unset($tree, $i, $value);

// Body tag
echo '<body class="level-'.count($servant->page()->tree()).' index-'.$servant->page()->index().' '.implode(' ', $classes).'">';
unset($classes);

?>

<!-- Page content -->
<?= $servant->template()->content() ?>

		<script src="<?= $servant->paths()->userAction('scripts', 'domain', $servant->action()->isRead() ? $tree = $servant->page()->tree() : array()) ?>"></script>
	</body>
</html>