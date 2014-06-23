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
