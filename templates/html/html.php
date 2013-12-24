<?php

/**
* Preparing meta info
*/
$meta = '';
$links = '';



/**
* Page title
*/
$title = (!$page->isHome() ? htmlspecialchars($page->name()).' &ndash; ' : '').htmlspecialchars($servant->site()->name());
$meta .= '<title>'.$title.'</title><meta property="og:title" content="'.$title.'">';
unset($title);



/**
* Site name
*/
$meta .= '<meta name="application-name" content="'.htmlspecialchars($servant->site()->name()).'">'
        .'<meta property="og:site_name" content="'.htmlspecialchars($servant->site()->name()).'">';



/**
* Description
*/
$description = htmlspecialchars(trim_text($servant->site()->description(), true));
if ($description) {
	$meta .= '<meta name="description" content="'.$description.'">'
	        .'<meta property="og:description" content="'.$description.'">';
}
unset($description);



/**
* Other Open Graph stuff
*/
$meta .= '<meta property="og:type" content="'.($page->isHome() ? 'website' : 'article').'">'
         .'<meta property="og:url" content="'.$servant->paths()->root('url').'">';



/**
* Splash image
*/
$splashImage = $servant->site()->splashImage('url');
if ($splashImage) {
	$links .= '<meta property="og:image" content="'.$splashImage.'">'
	         .'<meta name="msapplication-TileImage" content="'.$splashImage.'"/>'
	         .'<link rel="apple-touch-startup-image" href="'.$splashImage.'">';
}

/**
* Icon
*/
$icon = $servant->site()->icon('domain');
if ($icon) {
	$extension = pathinfo($icon, PATHINFO_EXTENSION);

	// .ico for browsers
	if ($extension === 'ico') {
		$links .= '<link rel="shortcut icon" href="'.$icon.'" type="image/x-icon">';

	// Image icons for browsers and various platforms
	} else {
		$links .= '<link rel="icon" href="'.$icon.'" type="'.$servant->constants()->contentTypes($extension).'">'
		        .'<link rel="apple-touch-icon" href="'.$icon.'" />'
		        .($splashImage ? '' : '<meta name="msapplication-TileImage" content="'.$icon.'"/>');
	}

	unset($extension);

}
unset($splashImage, $icon);



// Web app capabilities
// $meta .= '<meta name="mobile-web-app-capable" content="yes">'
//         .'<meta name="apple-mobile-web-app-capable" content="yes">';


/**
* Links to stylesheets (external from settings + internal from stylesheets action)
*/
$stylesheetsLinks = '';

// External stylesheets
$urls = $servant->site()->externalStylesheets();

// Assets
$urls[] = $servant->paths()->endpoint('sitestyles', 'domain');

// Page-specific stylesheets
if ($action->isSite()) {
	$urls[] = $servant->paths()->endpoint('pagestyles', 'domain', $page->tree());
}

// Generate HTML
foreach ($urls as $url) {
	$stylesheetsLinks .= '<link rel="stylesheet" type="text/css" href="'.$url.'" media="screen">';
}
unset($urls, $url);



/**
* Links to scripts (external from settings + internal from script actions)
*/
$scriptLinks = '';

// External scripts
$urls = $servant->site()->externalScripts();

// Assets
$urls[] = $servant->paths()->endpoint('sitescripts', 'domain');

// Page-specific scripts
if ($action->isSite()) {
	$urls[] = $servant->paths()->endpoint('pagescripts', 'domain', $page->tree());
}

// Generate HTML
foreach ($urls as $url) {
	$scriptLinks .= '<script type="text/javascript" src="'.$url.'"></script>';
}
unset($urls, $url);



/**
* Create classes for body
*/
$i = 1;
$temp = array();
$tree = $page->tree();
foreach ($tree as $value) {
	$temp[] = 'page-'.implode('-', array_slice($tree, 0, $i));
	$i++;
}
$bodyClasses = 'action-'.$action->id().' depth-'.$page->depth().' index-'.$page->index().' '.implode(' ', $temp).' template-'.$page->template();
unset($temp, $tree, $i, $value);

?>



<!DOCTYPE html>
<html lang="<?php echo $servant->site()->language() ?>">
	<head>

		<meta charset="utf-8">

		<?php echo $meta ?>

		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<meta name="misapplication-tap-highlight" content="no">
        <meta name="apple-mobile-web-app-status-bar-style" content="black">
		<style type="text/css">
			@-ms-viewport{width: device-width;}
			@-o-viewport{width: device-width;}
			@viewport{width: device-width;}
			body{-webkit-tap-highlight-color: transparent;}
		</style>

		<?php echo $links ?>

		<?php echo $stylesheetsLinks ?>

	</head>

	<body class="<?php echo $bodyClasses ?>">

		<?php echo $template->content() ?>

		<?php
		if ($servant->debug()) {
			echo $template->nest('debug');
		}
		?>

		<?php echo $scriptLinks ?>

	</body>
</html>
