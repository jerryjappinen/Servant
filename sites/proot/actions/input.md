
# Input



## Using validated input

The actual user input is available in the global variable `$input`. It has passed both basic validation and your custom validations in `action.php` and `output.php`.



## Defining accepted input

Validation schema for user input is simple. Proot will handle the actual validation when you define what you need.

#### `configuration.php`
	$action['input'] = array(

		// Require a valid record object and ID
		'app' => array(
			'type' => 'string',
			'values' => $available['apps']
		),

		// Any non-empty string
		'file' => array(
			'type' => 'string',
			'values' => $constants['patterns']['url']
		),

		// Optional links
		'links' => array(
			'type' => 'array',
			'default' => array()
		)

	);

As you can see in the example, for each parameter you create an array item defining *type*, accepted *values* and a *default* value.

If you provide a default value, it will be used when the user doesn't provide any value (effectively making the parameter optional). You can include additional restrictions to accepted values, either as an array of accepted values or a regular expression string.

If you set only *type*, you can write it in shorthand format:

#### `configuration.php`
	$action['input'] = array(
		'address' => 'string',
		'subject' => 'string',
		'content' => 'string'
	);



## Input types

<table>
	<tr>
		<th class="fifth">Type</th>
		<th>Values</th>
		<th>Result</th>
	</tr>
	<tr>
		<td class="fifth"><code>'boolean'</code></td>
		<td>&ndash;</td>
		<td><code>true</code> or <code>false</code></td>
	</tr>
	<tr>
		<td class="fifth"><code>'integer'</code></td>
		<td>&ndash;</td>
		<td>An integer</td>
	</tr>
	<tr>
		<td class="fifth"><code>'string'</code></td>
		<td>List of accepted values in an array, or a regular expression pattern as a string.</td>
		<td>A string</td>
	</tr>
	<tr>
		<td class="fifth"><code>'array'</code></td>
		<td>&ndash;</td>
		<td>An array</td>
	</tr>
	<tr>
		<td class="fifth"><code>'file'</code></td>
		<td>List of accepted file types in an array (MIME type or content type name).</td>
		<td>An array with <em>name</em>, <em>type</em>, <em>size</em> and temporary <em>location</em> of an uploaded file.</td>
	</tr>
</table>
