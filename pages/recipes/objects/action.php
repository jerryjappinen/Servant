<?php

try {

	$action1 = $servant->create()->action('js-vars');
	$action2 = $servant->create()->action('page', array('pointer' => array('page', 'foo', 'bar')));

	$reference = array(

		'Sample 1: Actions with no pointer' => array(
			'initialization' => array(
				'',
				'$action1 = $servant->create()->action("js-vars")',
			),
		),

		'Sample 2: Pass pointer to action' => array(
			'initialization' => array(
				'',
				'$action2 = $servant->create()->action("page", array("pointer" => array("page", "foo", "bar")))',
			),
		),

	);

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

} catch (Exception $e) {
	echo html_dump($e->getMessage());
}

?>
