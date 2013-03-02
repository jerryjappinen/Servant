
# Backend helpers

These PHP helpers are available when writing actions. They are declared in `backend/helpers/`.



<table>

	<tr>
		<th colspan="3">Arrays</th>
	</tr>

	<tr>
		<th class="discreet">Function</th>
		<th class="discreet">Example</th>
	</tr>

	<tr>
		<td><code>array_flatten($array)</code><br />Flattens an array. The result is an array with no child arrays. Original keys are removed, the result array has numerical indexes.</td>
		<td><pre><code></code></pre></td>
	</tr>

	<tr>
		<td><code>to_array($original)</code><br />Make sure a variable is an array, convert if needed</td>
		<td><pre><code></code></pre></td>
	</tr>

	<tr>
		<td><code>limplode($glue, $array, $last = false)</code><br />Allow giving a different last glue for implode</td>
		<td><pre><code></code></pre></td>
	</tr>



	<tr>
		<th colspan="3">Debugging</th>
	</tr>

	<tr>
		<td><code>debug($value)</code><br />Dump stuff into log</td>
		<td><pre><code>debug($paths)</code></pre></td>
	</tr>



	<tr>
		<th colspan="3">Files</th>
	</tr>

	<tr>
		<td><code>glob_dir($path = '')</code><br /></td>
		<td><pre><code></code></pre></td>
	</tr>

	<tr>
		<td><code>glob_files($path = '', $filetypes = array())</code><br /></td>
		<td><pre><code></code></pre></td>
	</tr>

	<tr>
		<td><code>rglob($path = '', $pattern = '*', $flags = 0)</code><br /></td>
		<td><pre><code></code></pre></td>
	</tr>

	<tr>
		<td><code>rglob_dir($path = '')</code><br /></td>
		<td><pre><code></code></pre></td>
	</tr>

	<tr>
		<td><code>rglob_files($path = '', $filetypes = array())</code><br /></td>
		<td><pre><code></code></pre></td>
	</tr>

	<tr>
		<td><code>rrmdir($directory)</code><br /></td>
		<td><pre><code>Recursively empty and remove a directory.</code></pre></td>
	</tr>



	<tr>
		<th colspan="3">Strings</th>
	</tr>

	<tr>
		<td><code>start_with($string, $start = '')</code><br />Make sure initial characters of a string are what they need to be.</td>
		<td><pre><code>$string = 'some/path/from/somewhere';
$string = start_with($string, '/');    // a slash added: '/some/path/from/somewhere'
$string = start_with($string, '/');    // still '/some/path/from/somewhere'</code></pre></td>
	</tr>

	<tr>
		<td><code>dont_start_with($string, $start = '')</code><br />Make sure initial characters of a string are <strong>not</strong> what they shouldn't to be.</td>
		<td><pre><code>$string = 'http://eiskis.net/proot/guides/';
$string = dont_start_with($string, 'http://');    // 'eiskis.net/proot/guides/'
$string = dont_start_with($string, 'http://');    // still 'eiskis.net/proot/guides/'</code></pre></td>
	</tr>

	<tr>
		<td><code>end_with($string, $end = '')</code><br />Make sure the final characters of a string are what they need to be.</td>
		<td><pre><code></code></pre></td>
	</tr>

	<tr>
		<td><code>dont_end_with($string, $end = '')</code><br />Make sure the final characters of a string are <strong>not</strong> what they shouldn't to be.</td>
		<td><pre><code></code></pre></td>
	</tr>

	<tr>
		<td><code>detect($string, $mode = 'type')</code><br /></td>
		<td><pre><code></code></pre></td>
	</tr>

	<tr>
		<td><code>shorthand_decode($string)</code><br />Decodes a string into an array.</td>
		<td><pre><code>shorthand_decode('some value;1,2;key:Value,anotherKey:Another value');
/* array(
	0 => 'Some value',
	1 => array(
		0 => 100,
		1 => 400
	),
	2 => array(
		'key' => 'Value',
		'anotherKey' => 'Another value'
	)
) */</code></pre></td>
	</tr>

	<tr>
		<td><code>cachename_encode($data)</code><br />Generate a non-human-readable, unique string for arbitrary data. Original data is serialized, with order preserved but keys dropped.</td>
		<td><pre><code></code></pre></td>
	</tr>

	<tr>
		<td><code>cachename_decode($string)</code><br />Decode the aforementioned back to original data. Possible keys of original data are not restored.</td>
		<td><pre><code></code></pre></td>
	</tr>



	<tr>
		<th colspan="3">Validations</th>
	</tr>

	<tr>
		<td><code>normalize($value, $type)</code><br />Interpret intent of input value and attempt to convert into desired type.</td>
		<td><pre><code>normalize('some string', 'array');          // array('some string')
normalize(array(1, 2, 3), 'array');         // array(1, 2, 3)
normalize(array(1, 2, 3), 'string');        // '1, 2, kolme'</code></pre></td>
	</tr>

	<tr>
		<td><code>validate($name, $schema, $input)</code><br />Test an input value against a validation schema.</td>
		<td><pre><code></code></pre></td>
	</tr>

	<tr>
		<td><code>run_validations($input, $schemas)</code><br />Run validate() for a set of values and schemas.</td>
		<td><pre><code></code></pre></td>
	</tr>

</table>
