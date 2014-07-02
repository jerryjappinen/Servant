<?php

try {

	$reference = array(
		'initialization' => array(
			'initialize' => array(
				'$json = null',
			),
		),

		'getters' => array(
			'actions' => array(
				'',
				'$servant->constants()->actions()',
				$servant->constants()->actions(),
			),
			'contentTypes' => array(
				'',
				'$servant->constants()->contentTypes()',
				$servant->constants()->contentTypes(),
			),
			'defaults' => array(
				'',
				'$servant->constants()->defaults()',
				$servant->constants()->defaults(),
			),
			'formats' => array(
				'',
				'$servant->constants()->formats()',
				$servant->constants()->formats(),
			),
			'namingConvention' => array(
				'',
				'$servant->constants()->namingConvention()',
				$servant->constants()->namingConvention(),
			),
			'patterns' => array(
				'',
				'$servant->constants()->patterns()',
				$servant->constants()->patterns(),
			),
			'statuses' => array(
				'',
				'$servant->constants()->statuses()',
				$servant->constants()->statuses(),
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
