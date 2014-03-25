<?php

/**
* HTML meta tags and boilerplate code
*
* NESTED TEMPLATES
*	debug
*
* CONTENT PARAMETERS
*	0: Body content (string)
*	1: Current page (ServantPage)
*	2: usePageAssets (truy or falsy)		// FLAG this is true if $page is not available?
*/

$page = $template->content(1);
$usePageAssets = $template->content(2) ? true : false;



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
$urls[] = $servant->paths()->endpoint('templatestyles', 'domain', $page->template());

// Page-specific stylesheets
if ($usePageAssets and $page->stylesheets()) {
	$urls[] = $servant->paths()->endpoint('pagestyles', 'domain', $page->pointer());
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
$urls[] = $servant->paths()->endpoint('templatescripts', 'domain', $page->template());

// Page-specific scripts
if ($usePageAssets and $page->scripts()) {
	$urls[] = $servant->paths()->endpoint('pagescripts', 'domain', $page->pointer());
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
$pointer = $page->pointer();
foreach ($pointer as $value) {
	$temp[] = 'page-'.implode('-', array_slice($pointer, 0, $i));
	$i++;
}
$bodyClasses = 'depth-'.$page->depth().' index-'.$page->index().' '.implode(' ', $temp).' template-'.$page->template();
unset($temp, $pointer, $i, $value);

?>



<!DOCTYPE html>
<html<?php echo $servant->site()->language() ? ' lang="'.$servant->site()->language().'"' : '' ?>>
	<head>

		<meta charset="utf-8">

		<?php echo $meta ?>

		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimal-ui">
		<meta name="msapplication-tap-highlight" content="no">
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
