<?php

try {

	$reference = array(

		'initialization' => array(
			'initialize' => array(
				array('$path', '$parent', '$id = null'),
			),
		),

		'node traversal' => array(
			'isRoot' => array(
				'',
				'$page->isRoot()',
				$page->isRoot(),
			),
			'next' => array(),
			'parent' => array(
				'',
			),
			'parents' => array(
				'$includeRoot = false',
			),
			'previous' => array(),
			'root' => array(),
			'sibling' => array(),
			'siblings' => array(),
			'tree' => array(
				'$includeRoot = false',
			),
		),

		'node getters' => array(
			'depth' => array(
				'$includeRoot = false',
				'$page->depth()',
				$page->depth(),
			),
			'description' => array(
				'',
				'$page->description()',
				$page->description(),
			),
			'icon' => array(
				'$format = false',
				'$page->icon()',
				$page->icon(),
			),
			'id' => array(
				'',
				'$page->id()',
				$page->id(),
			),
			'index' => array(
				'',
				'$page->index()',
				$page->index(),
			),
			'language' => array(
				'',
				'$page->language()',
				$page->language(),
			),
			'name' => array(
				'',
				'$page->name()',
				$page->name(),
			),
			'pointer' => array(
				'$includeRoot = false',
				'$page->pointer()',
				$page->pointer(),
			),
			'siteName' => array(
				'',
				'$page->siteName()',
				$page->siteName(),
			),
			'splashImage' => array(
				'$format = false',
				'$page->splashImage()',
				$page->splashImage(),
			),
			'stringPointer' => array(
				'$includeRoot = false',
				'$page->stringPointer()',
				$page->stringPointer(),
			),
			'template' => array(
				'',
				'$page->template()',
				$page->template(),
			),
		),

		'page traversal' => array(
			'category' => array(
				'',
			),
			'children' => array(
				'',
			),
			'page' => array(
				'',
			),
			'isCategory' => array(
				'',
				'$page->isCategory()',
				$page->isCategory(),
			),
			'isHome' => array(
				'',
				'$page->isHome()',
				$page->isHome(),
			),
			'isPage' => array(
				'',
				'$page->isPage()',
				$page->isPage(),
			),
		),

		'page getters' => array(
			'endpoint' => array(
				'$format = false',
				'$page->endpoint()',
				$page->endpoint(),
			),
			'output' => array(
				'',
			),
			'path' => array(
				'$format = false',
				'$page->path()',
				$page->path(),
			),
			'scripts' => array(
				'$format = false',
				'$page->scripts()',
				$page->scripts(),
			),
			'stylesheets' => array(
				'$format = false',
				'$page->stylesheets()',
				$page->stylesheets(),
			),
			'type' => array(
				'',
				'$page->type()',
				$page->type(),
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
		<tr><td><code>'.$methodName.'('.(isset($methodDetails[0]) ? (is_array($methodDetails[0]) ? implode(', ', $methodDetails[0]) : $methodDetails[0]) : '').')</code>'.(isset($methodDetails[1]) ? '<br><em><code>'.$methodDetails[1].'</code></em>' : '').'</td>
		<td>'.(count($methodDetails) >= 3 ? html_dump($methodDetails[2]) : '<em>&ndash;</em>').'</td>';
	}
}
echo '</table>';
?>
