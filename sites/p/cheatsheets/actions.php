<?php

$output = '
# Backend actions

#### **This guide is created dynamically. When you access it locally, it will reflect what\'s available in your Proot installation.**

<table>
<tbody>


';

// Documentation
$temp = array(

	'about' => array(
		
		'apps' => array(
			'input' => 'type (optional)',
		),

		'proot' => array(
			'input' => 'None',
		),

		'request' => array(
			'input' => 'None',
		),

	),

);

// List all actions
$i = 0;
foreach (glob_dir('../backend/actions/') as $value) {

	// Heading
	if ($i === 0) {
		$output .= '
			<tr>
				<th>'.basename($value).'</th>
				<th class="discreet">Input</th>
				<th class="discreet">Output</th>
			</tr>
		';
	} else {
		$output .= '
			<tr>
				<th colspan="3">'.basename($value).'</th>
			</tr>
		';
	}

	// Actions
	foreach (glob_dir($value) as $value2) {
		$value2 = basename($value2);
		$output .= '
			<tr>
				<td><code>'.$value2.'</code></td>
				<td>'.(!empty($temp[basename($value)][$value2]['input']) ? $temp[basename($value)][$value2]['input'] : '').'</td>
				<td>'.(!empty($temp[basename($value)][$value2]['output']) ? $temp[basename($value)][$value2]['output'] : '').'</td>
			</tr>
		';
	}

	$i++;
}

$output .= '
</tbody>
</table>
';

echo Markdown($output);

?>