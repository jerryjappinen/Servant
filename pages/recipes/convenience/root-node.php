<?php

try {

	$root = $servant->sitemap()->root();

	$reference = array(

		'Sitemap' => array(
			'select root' => array(
				'',
				'$servant->sitemap()->root()',
			),
		),

		'node traversal' => array(
			'isRoot' => array(
				'',
				'$root->isRoot()',
				$root->isRoot(),
			),
			'isPage' => array(
				'',
				'$root->isPage()',
				$root->isPage(),
			),
			'isCategory' => array(
				'',
				'$root->isCategory()',
				$root->isCategory(),
			),
			'next' => array(''),
			'parent' => array(''),
			'parents' => array(
				'$includeRoot = false',
			),
			'previous' => array(''),
			'root' => array(''),
			'sibling' => array(''),
			'siblings' => array(''),
			'tree' => array(
				'$includeRoot = false',
			),
		),

		'node getters' => array(
			'browserCache' => array(
				'',
				'$root->browserCache()',
				$root->browserCache(),
			),
			'depth' => array(
				'$includeRoot = false',
				'$root->depth()',
				$root->depth(),
			),
			'description' => array(
				'',
				'$root->description()',
				$root->description(),
			),
			'externalScripts' => array(
				'',
				'$root->externalScripts("url")',
				$root->externalScripts('url'),
			),
			'externalStylesheets' => array(
				'',
				'$root->externalStylesheets("url")',
				$root->externalStylesheets('url'),
			),
			'icon' => array(
				'$format = false',
				'$root->icon()',
				$root->icon(),
			),
			'id' => array(
				'',
				'$root->id()',
				$root->id(),
			),
			'index' => array(
				'',
				'$root->index()',
				$root->index(),
			),
			'language' => array(
				'',
				'$root->language()',
				$root->language(),
			),
			'name' => array(
				'',
				'$root->name()',
				$root->name(),
			),
			'pointer' => array(
				'$includeRoot = false',
				'$root->pointer()',
				$root->pointer(),
			),
			'serverCache' => array(
				'',
				'$root->serverCache()',
				$root->serverCache(),
			),
			'siteName' => array(
				'',
				'$root->siteName()',
				$root->siteName(),
			),
			'splashImage' => array(
				'$format = false',
				'$root->splashImage()',
				$root->splashImage(),
			),
			'stringPointer' => array(
				'$includeRoot = false',
				'$root->stringPointer()',
				$root->stringPointer(),
			),
			'template' => array(
				'',
				'$root->template()',
				$root->template(),
			),
		),

		'page traversal' => array(
			'allPages' => array(
				'',
				'$page->allPages()'
			),
			'categories' => array(
				'',
				'$page->categories()'
			),
			'category' => array(
				'',
			),
			'children' => array(
				'',
			),
			'isCategory' => array(
				'',
				'$root->isCategory()',
				$root->isCategory(),
			),
			'isHome' => array(
				'',
				'$root->isHome()',
				$root->isHome(),
			),
			'isPage' => array(
				'',
				'$root->isPage()',
				$root->isPage(),
			),
			'page' => array(
				'',
			),
			'pages' => array(
				'',
				'$page->pages()'
			),
		),

		'page getters' => array(
			'endpoint' => array(
				'$format = false',
				'$root->endpoint()',
				$root->endpoint(),
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
