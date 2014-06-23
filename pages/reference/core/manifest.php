<?php

try {

	$reference = array(
		'initialization' => array(
			'initialize' => array(
				'$path = null',
			),
		),

		'convenience' => array(
			'removeRootNodeValue' => array(
				'$values',
				'$servant->manifest()->removeRootNodeValue(array(\'\' => \'A\', \'docs\' => \'B\'))',
				$servant->manifest()->removeRootNodeValue(array('' => 'A', 'docs' => 'B')),
			),
		),

		'getters' => array(
			'defaultBrowserCache' => array(
				'',
				'$servant->manifest()->defaultBrowserCache()',
				$servant->manifest()->defaultBrowserCache(),
			),
			'defaultDescription' => array(
				'',
				'$servant->manifest()->defaultDescription()',
				$servant->manifest()->defaultDescription(),
			),
			'defaultIcon' => array(
				'',
				'$servant->manifest()->defaultIcon()',
				$servant->manifest()->defaultIcon(),
			),
			'defaultLanguage' => array(
				'',
				'$servant->manifest()->defaultLanguage()',
				$servant->manifest()->defaultLanguage(),
			),
			'defaultPageName' => array(
				'',
				'$servant->manifest()->defaultPageName()',
				$servant->manifest()->defaultPageName(),
			),
			'defaultServerCache' => array(
				'',
				'$servant->manifest()->defaultServerCache()',
				$servant->manifest()->defaultServerCache(),
			),
			'defaultSiteName' => array(
				'',
				'$servant->manifest()->defaultSiteName()',
				$servant->manifest()->defaultSiteName(),
			),
			'defaultSplashImage' => array(
				'',
				'$servant->manifest()->defaultSplashImage()',
				$servant->manifest()->defaultSplashImage(),
			),
			'defaultTemplate' => array(
				'',
				'$servant->manifest()->defaultTemplate()',
				$servant->manifest()->defaultTemplate(),
			),
			'browserCaches' => array(
				'$key = null',
				'$servant->manifest()->browserCaches()',
				$servant->manifest()->browserCaches(),
			),
			'descriptions' => array(
				'$key = null',
				'$servant->manifest()->descriptions()',
				$servant->manifest()->descriptions(),
			),
			'icons' => array(
				'$key = null',
				'$servant->manifest()->icons()',
				$servant->manifest()->icons(),
			),
			'languages' => array(
				'$key = null',
				'$servant->manifest()->languages()',
				$servant->manifest()->languages(),
			),
			'pageNames' => array(
				'$key = null',
				'$servant->manifest()->pageNames()',
				$servant->manifest()->pageNames(),
			),
			'serverCaches' => array(
				'$key = null',
				'$servant->manifest()->serverCaches()',
				$servant->manifest()->serverCaches(),
			),
			'siteNames' => array(
				'$key = null',
				'$servant->manifest()->siteNames()',
				$servant->manifest()->siteNames(),
			),
			'splashImages' => array(
				'$key = null',
				'$servant->manifest()->splashImages()',
				$servant->manifest()->splashImages(),
			),
			'templates' => array(
				'$key = null',
				'$servant->manifest()->templates()',
				$servant->manifest()->templates(),
			),
			'defaultScripts' => array(
				'',
				'$servant->manifest()->defaultScripts()',
				$servant->manifest()->defaultScripts(),
			),
			'defaultStylesheets' => array(
				'',
				'$servant->manifest()->defaultStylesheets()',
				$servant->manifest()->defaultStylesheets(),
			),
			'scripts' => array(
				'$key = null',
				'$servant->manifest()->scripts()',
				$servant->manifest()->scripts(),
			),
			'stylesheets' => array(
				'$key = null',
				'$servant->manifest()->stylesheets()',
				$servant->manifest()->stylesheets(),
			),
			'sitemap' => array(
				'',
				'$servant->manifest()->sitemap()',
				$servant->manifest()->sitemap(),
			),
		),

	);

} catch (Exception $e) {
	echo html_dump($e->getMessage());
}

// Printout
echo '<table>';
foreach ($reference as $section => $methods) {
	echo '<tr><th colspan="4"><h3 class="reset">'.ucfirst($section).'</h3></tr>';
	foreach ($methods as $methodName => $methodDetails) {
		echo '
		<tr><td><code>'.$methodName.'('.(isset($methodDetails[0]) ? (is_array($methodDetails[0]) ? implode(', ', $methodDetails[0]) : $methodDetails[0]) : '').')</code>'.(isset($methodDetails[1]) ? '<br><em><code>'.$methodDetails[1].'</code></em>' : '').'</td>
		<td>'.(count($methodDetails) >= 3 ? html_dump($methodDetails[2]) : '<em>&ndash;</em>').'</td>';
	}
}
echo '</table>';
?>
