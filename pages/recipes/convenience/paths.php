<?php

try {

	$reference = array(

		'Path getters' => array(
			'endpoint' => array(
				'',
				'$servant->paths()->endpoint("site", "domain")',
				$servant->paths()->endpoint("site", "domain"),
			),

			'endpoints' => array(
				'',
				'$servant->paths()->endpoints()',
				$servant->paths()->endpoints(),
			),

			'endpoints domain' => array(
				'',
				'$servant->paths()->endpoints("domain")',
				$servant->paths()->endpoints('domain'),
			),

			'endpoints server' => array(
				'',
				'$servant->paths()->endpoints("server")',
				$servant->paths()->endpoints('server'),
			),

			'endpoints url' => array(
				'',
				'$servant->paths()->endpoints("url")',
				$servant->paths()->endpoints('url'),
			),

		),

		'Internal paths' => array(

			'relative to url' => array(
				'',
				'$servant->paths()->format("foo", "url")',
				$servant->paths()->format('foo', 'url'),
			),

			'relative to server' => array(
				'',
				'$servant->paths()->format("foo", "server")',
				$servant->paths()->format('foo', 'server'),
			),

			'relative to domain' => array(
				'',
				'$servant->paths()->format("foo", "domain")',
				$servant->paths()->format('foo', 'domain'),
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
		),

		'internal absolute paths' => array(
			'to url' => array(
				'',
				'$servant->paths()->format("http://localhost/servant/foo", "url")',
				$servant->paths()->format('http://localhost/servant/foo', 'url'),
			),

			'to domain' => array(
				'',
				'$servant->paths()->format("http://localhost/servant/foo", "domain")',
				$servant->paths()->format('http://localhost/servant/foo', 'domain'),
			),
			'to server' => array(
				'',
				'$servant->paths()->format("http://localhost/servant/foo", "server")',
				$servant->paths()->format('http://localhost/servant/foo', 'server'),
			),
		),

		'external paths' => array(

			'to url' => array(
				'',
				'$servant->paths()->format("http://eiskis.net/foo", "url")',
				$servant->paths()->format('http://eiskis.net/foo', 'url'),
			),

			'to domain' => array(
				'',
				'$servant->paths()->format("http://eiskis.net/foo", "domain")',
				$servant->paths()->format('http://eiskis.net/foo', 'domain'),
			),

			'to server' => array(
				'',
				'$servant->paths()->format("http://eiskis.net/foo", "server")',
				$servant->paths()->format('http://eiskis.net/foo', 'server'),
			),

			'force external from server to domain' => array(
				'',
				'$servant->paths()->format("http://eiskis.net/foo", "domain", "server")',
				$servant->paths()->format('http://eiskis.net/foo', 'domain', 'server'),
			),

			'force external from domain to server' => array(
				'',
				'$servant->paths()->format("http://eiskis.net/foo", "server", "domain")',
				$servant->paths()->format('http://eiskis.net/foo', 'server', 'domain'),
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
