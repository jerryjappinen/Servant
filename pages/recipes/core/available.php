<?php

try {

	$reference = array(

		'lists' => array(
			'utilities' => array(
				'',
				'$servant->available()->utilities()',
				$servant->available()->utilities(),
			),
			'actions' => array(
				'',
				'$servant->available()->actions()',
				$servant->available()->actions(),
			),
			'templates' => array(
				'',
				'$servant->available()->templates()',
				$servant->available()->templates(),
			),
		),

		'ask' => array(
			'utility' => array(
				'',
				'$servant->available()->utility("validator")',
				$servant->available()->utility("validator"),
			),
			'action' => array(
				'',
				'$servant->available()->action("page")',
				$servant->available()->action("page"),
			),
			'template' => array(
				'',
				'$servant->available()->template("html")',
				$servant->available()->template("html"),
			),
		),

		'getters' => array(
			'first utility' => array(
				'',
				'$servant->available()->utilities(0)',
				$servant->available()->utilities(0),
			),
			'first action' => array(
				'',
				'$servant->available()->actions(0)',
				$servant->available()->actions(0),
			),
			'first template' => array(
				'',
				'$servant->available()->templates(0)',
				$servant->available()->templates(0),
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
