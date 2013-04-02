

# `function calculate_string ($formula = '', $forceInteger = false)`

**Do a calculation with a formula in a string.**

This function accepts a mathematical formula as a string, calculates the result and returns it. When `$forceInteger` is set to true, the return value will be rounded to an integer if it would otherwise be a float value.

[View source](https://bitbucket.org/Eiskis/baseline-php/src/default/source/strings/calculate_string.php?at=default)



## I/O examples

<table>

	<tr>
		<th scope="col">Input</th>
		<th scope="col">Return value</th>
		<th scope="col">Notes</th>
	</tr>

	<tr>
		<td><code>'12*200'</code></td>
		<td><code>2400</code></td>
		<td></td>
	</tr>

	<tr>
		<td><code>'52*7*24*60*60'</code></td>
		<td><code>31449600</code></td>
		<td></td>
	</tr>

</table>



## Real-life examples

### Parse formulas in JSON

JSON is a common format for sending and storing structured information, but does not support mathematical formulas as such. If you are using JSON for storing settings of your PHP program, `calculate_string()` allows you to store numerical values as strings that include mathematical formulas.

##### settings.json
	{
		"sessionTimeout": "12*60*60"
	}

##### index.php
	// Parse JSON settings
	$settings = parse_json(file_get_contents('settings.json'));

	// Use JSON values as 
	if (isset($settings['sessionTimeout'])) {
		$settings['sessionTimeout'] = calculate_string($settins['sessionTimeout']);
	}

	echo $settings['sessionTimeout'];		// 43200
