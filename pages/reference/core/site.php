<?php

try {

	$reference = array(

		'getters' => array(
			'scripts' => array(
				'$format = false',
				'$servant->site()->scripts()',
				$servant->site()->scripts(),
			),
			'stylesheets' => array(
				'$format = false',
				'$servant->site()->stylesheets()',
				$servant->site()->stylesheets(),
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
