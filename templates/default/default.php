<?php
$bodyClasses = '';
$meta = '';



/**
* Preparing meta info
*/

// Page title
$title = (!$servant->page()->isHome() ? $servant->page()->name().' &ndash; ' : '').$servant->site()->name();
$meta .= '<title>'.$title.'</title><meta property="og:title" content="'.$title.'">';
unset($title);

// Site name
$meta .= '<meta name="application-name" content="'.$servant->site()->name().'"><meta property="og:site_name" content="'.$servant->site()->name().'">';

// Description
$description = trim_text($servant->site()->description(), true);
if ($description) {
	$meta .= '<meta name="description" content="'.$description.'"><meta property="og:description" content="'.$description.'">';
}
unset($description);

// Other Open Graph stuff
$meta .= '<meta property="og:type" content="'.($servant->page()->isHome() ? 'website' : 'article').'"><meta property="og:url" content="'.$servant->paths()->root('url').'">';



// Splash image
$splashImage = $servant->site()->splashImage('url');
if ($splashImage) {
	$meta .= '<meta property="og:image" content="'.$splashImage.'"><meta name="msapplication-TileImage" content="'.$splashImage.'"/>';
}

// Icon
$icon = $servant->site()->icon('domain');
if ($icon) {
	$extension = pathinfo($icon, PATHINFO_EXTENSION);

	// .ico for browsers
	if ($extension === 'ico') {
		$meta .= '<link rel="shortcut icon" href="'.$icon.'" type="image/x-icon">';

	// Image icons for browsers and various platforms
	} else {
		$meta .= '<link rel="icon" href="'.$icon.'" type="'.$servant->settings()->contentTypes($extension).'"><link rel="apple-touch-icon-precomposed" href="'.$icon.'" />';
		echo ($splashImage ? '' : '<meta name="msapplication-TileImage" content="'.$icon.'"/>');
	}

	unset($extension);

}
unset($splashImage, $icon);



/**
* Asset links
*/
$stylesheetsLink = $servant->paths()->userAction('stylesheets', 'domain', ($action->isRead() ? $tree = $servant->page()->tree() : array()));
$scriptsLink = $servant->paths()->userAction('scripts', 'domain', $action->isRead() ? $tree = $servant->page()->tree() : array());



/**
* Create classes for body
*/
$i = 1;
$temp = array();
$tree = $servant->page()->tree();
foreach ($tree as $value) {
	$temp[] = 'page-'.implode('-', array_slice($tree, 0, $i));
	$i++;
}
$bodyClasses = 'level-'.count($servant->page()->tree()).' index-'.$servant->page()->index().' '.implode(' ', $temp);
unset($temp, $tree, $i, $value);

?>



<!DOCTYPE html>
<html lang="<?php echo $servant->site()->language() ?>">
	<head>

		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<style type="text/css">
			@-ms-viewport{width: device-width;}
			@-o-viewport{width: device-width;}
			@viewport{width: device-width;}
		</style>

		<?php echo $meta ?>

		<link rel="stylesheet" href="<?php echo $stylesheetsLink ?>" media="screen">

	</head>

	<body class="<?php echo $bodyClasses ?>">

		<?php echo $template->content() ?>

		<?php echo $servant->debug() ? $template->nest('debug') : '' ?>

		<script src="<?php echo $scriptsLink ?>"></script>
	</body>
</html>