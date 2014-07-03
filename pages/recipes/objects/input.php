<?php

try {

	$input1 = $servant->create()->input();
	$input2 = $servant->create()->input('foo', 'bar');
	$input3 = $servant->create()->input(
		array(
			"pointer" => array(
				"foo",
				"bar",
			),
		),
		array(
			"anykey" => "some string value",
			"someOtherKey" => array(1, 2, 3),
		)
	);

	$reference = array(

		'Sample 1: Empty input' => array(
			'initialization' => array(
				'',
				'$input1 = $servant->create()->input()',
			),
			'pointer' => array(
				'',
				'$input1->pointer()',
				$input1->pointer(),
			),
			'stringPointer' => array(
				'',
				'$input1->stringPointer()',
				$input1->stringPointer(),
			),
		),

		'Sample 2: Quickly initialize input with pointer' => array(
			'initialization' => array(
				'',
				'$input2 = $servant->create()->input("foo", "bar")',
			),
			'fetch' => array(
				'$key, $format, $default = null',
				'$input2->fetch("foo", "string")',
			),
			'formats' => array(
				'',
				'$input2->formats()',
				$input2->formats(),
			),
			'pointer' => array(
				'',
				'$input2->pointer()',
				$input2->pointer(),
			),
			'stringPointer' => array(
				'',
				'$input2->stringPointer()',
				$input2->stringPointer(),
			),
		),

		'Sample 3: Full control over input' => array(
			'initialization' => array(
				'',
				'',
'$input3 = servant->create()->input(
	array(
		"pointer" => array(
			"foo",
			"bar",
		),
	),
	array(
		"anykey" => "some string value",
		"someOtherKey" => array(1, 2, 3),
	)
)',
			),
			'fetch' => array(
				'$key, $format, $default = null',
				'$input3->fetch("anykey", "string")',
				$input3->fetch("anykey", "string"),
			),
			'formats' => array(
				'',
				'$input3->formats()',
				$input3->formats(),
			),
			'pointer' => array(
				'',
				'$input3->pointer()',
				$input3->pointer(),
			),
			'stringPointer' => array(
				'',
				'$input3->stringPointer()',
				$input3->stringPointer(),
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
