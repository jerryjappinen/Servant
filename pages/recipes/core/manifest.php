<?php

try {

	$reference = array(
		'initialization' => array(
			'initialize' => array(
				'$path = null',
			),
		),

		'getters' => array(
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
			'redirects' => array(
				'$key = null',
				'$servant->manifest()->redirects()',
				$servant->manifest()->redirects(),
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
foreach ($reference as $section => $methods) {
	echo '<h3>'.ucfirst($section).'</h3>';
	echo '<table>';
	foreach ($methods as $methodName => $methodDetails) {
		echo '
		<tr><td><code>'.$methodName.(isset($methodDetails[0]) ? '('.(is_array($methodDetails[0]) ? implode(', ', $methodDetails[0]) : $methodDetails[0]).')' : '').'</code>'.(isset($methodDetails[1]) ? '<br><em><code>'.$methodDetails[1].'</code></em>' : '').'</td>
		<td>'.(count($methodDetails) >= 3 ? html_dump($methodDetails[2]) : '<em>&ndash;</em>').'</td>';
	}
	echo '</table>';
}
?>
