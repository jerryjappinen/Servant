<?php

try {

	$reference = array(

		'getters' => array(
			'browserCache' => array(
				'',
				'$servant->site()->browserCache()',
				$servant->site()->browserCache(),
			),
			'description' => array(
				'',
				'$servant->site()->description()',
				$servant->site()->description(),
			),
			'externalScripts' => array(
				'',
				'$servant->site()->externalScripts()',
				$servant->site()->externalScripts(),
			),
			'externalStylesheets' => array(
				'',
				'$servant->site()->externalStylesheets()',
				$servant->site()->externalStylesheets(),
			),
			'icon' => array(
				'$format = null',
				'$servant->site()->icon()',
				$servant->site()->icon(),
			),
			'language' => array(
				'',
				'$servant->site()->language()',
				$servant->site()->language(),
			),
			'name' => array(
				'',
				'$servant->site()->name()',
				$servant->site()->name(),
			),
			'pageDescriptions' => array(
				'',
				'$servant->site()->pageDescriptions()',
				$servant->site()->pageDescriptions(),
			),
			'pageNames' => array(
				'',
				'$servant->site()->pageNames()',
				$servant->site()->pageNames(),
			),
			'pageOrder' => array(
				'',
				'$servant->site()->pageOrder()',
				$servant->site()->pageOrder(),
			),
			'pageTemplates' => array(
				'',
				'$servant->site()->pageTemplates()',
				$servant->site()->pageTemplates(),
			),
			'scripts' => array(
				'$format = false',
				'$servant->site()->scripts()',
				$servant->site()->scripts(),
			),
			'serverCache' => array(
				'',
				'$servant->site()->serverCache()',
				$servant->site()->serverCache(),
			),
			'splashImage' => array(
				'$format = null',
				'$servant->site()->splashImage()',
				$servant->site()->splashImage(),
			),
			'stylesheets' => array(
				'$format = false',
				'$servant->site()->stylesheets()',
				$servant->site()->stylesheets(),
			),
			'template' => array(
				'',
				'$servant->site()->template()',
				$servant->site()->template(),
			),
		),

	);

} catch (Exception $e) {
	echo html_dump($e->getMessage());
}

// Printout
echo '<h1>'.$page->name().'</h1>';
foreach ($reference as $section => $methods) {

	// Header
	echo '<h2>'.ucfirst($section).'</h2>';

	// Each method
	foreach ($methods as $methodName => $methodDetails) {
		echo '
		<h3><code>'.$methodName.'('.(is_array($methodDetails[0]) ? implode(', ', $methodDetails[0]) : $methodDetails[0]).')</code></h3>
		'.(isset($methodDetails[1]) ? '<p><code>'.$methodDetails[1].'</code></p>' : '').'
		'.(count($methodDetails) >= 3 ? html_dump($methodDetails[2]) : '');
	}

}

?>
