<?php

try {

	$reference = array(
		'path tests' => array(

			'relative to url' => array(
				'',
				'$servant->paths()->format("foo", "url")',
				$servant->paths()->format('foo', 'url'),
			),

			'root-relative to url' => array(
				'',
				'$servant->paths()->format("/foo", "url")',
				$servant->paths()->format('/foo', 'url'),
			),

			'root-relative to server' => array(
				'',
				'$servant->paths()->format("/foo", "server")',
				$servant->paths()->format('/foo', 'server'),
			),

			'root-relative to domain' => array(
				'',
				'$servant->paths()->format("/foo", "domain")',
				$servant->paths()->format('/foo', 'domain'),
			),

			'internal absolute to url' => array(
				'',
				'$servant->paths()->format("http://localhost/servant/foo", "url")',
				$servant->paths()->format('http://localhost/servant/foo', 'url'),
			),

			'external absolute to url' => array(
				'',
				'$servant->paths()->format("http://eiskis.net/foo", "url")',
				$servant->paths()->format('http://eiskis.net/foo', 'url'),
			),

			'internal absolute to domain' => array(
				'',
				'$servant->paths()->format("http://localhost/servant/foo", "domain")',
				$servant->paths()->format('http://localhost/servant/foo', 'domain'),
			),

			'external absolute to domain' => array(
				'',
				'$servant->paths()->format("http://eiskis.net/foo", "domain")',
				$servant->paths()->format('http://eiskis.net/foo', 'domain'),
			),

			'internal absolute to server' => array(
				'',
				'$servant->paths()->format("http://localhost/servant/foo", "server")',
				$servant->paths()->format('http://localhost/servant/foo', 'server'),
			),

			'external absolute to server' => array(
				'',
				'$servant->paths()->format("http://eiskis.net/foo", "server")',
				$servant->paths()->format('http://eiskis.net/foo', 'server'),
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
		<tr><td><code>'.$methodName.'('.(is_array($methodDetails[0]) ? implode(', ', $methodDetails[0]) : $methodDetails[0]).')</code>'.(isset($methodDetails[1]) ? '<br><em><code>'.$methodDetails[1].'</code></em>' : '').'</td>
		<td>'.(count($methodDetails) >= 3 ? html_dump($methodDetails[2]) : '<em>&ndash;</em>').'</td>';
	}
}
echo '</table>';
?>
