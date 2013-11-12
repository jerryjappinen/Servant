<?php

$home = create_object(new ServantPageNode($servant))->init('about/home.md')->name('Esa pekka')->output('foo');

$home->addChild('get-started/get-started.md', 'get-started/installation.md', 'get-started/pages.md');

$children = $home->children();
$child = $children[1];

$child->addChild('how-it-works/features/features.md');
$grandChildren = $child->children();
$grandChild = $grandChildren[0];

$output = array(

	'home' => array(

		// This page
		'id' => $child->parent()->id(),
		'parent' => $child->parent()->parent(),
		'name' => $child->parent()->name(),
		'path' => $child->parent()->path(),
		'home' => $child->parent()->home(),
		'depth' => $child->parent()->depth(),
		'index' => $child->parent()->index(),
		'tree' => implode('/', $child->parent()->tree()),

		// Other pages
		'children' => $child->parent()->listChildren('path'),
		'parents' => $child->parent()->listParents(),
		'siblings' => $child->parent()->listSiblings(),

	),

	'child[1]' => array(

		// This page
		'id' => $child->id(),
		'parent' => $child->parent()->id(),
		'name' => $child->name(),
		'path' => $child->path(),
		'home' => $child->home(),
		'depth' => $child->depth(),
		'index' => $child->index(),
		'tree' => implode('/', $child->tree()),

		// Other pages
		'children' => $child->listChildren('path'),
		'parents' => $child->listParents(),
		'siblings' => $child->listSiblings(),

	),

	'grandChild[0]' => array(

		// This page
		'id' => $grandChild->id(),
		'parent' => $child->parent()->id(),
		'name' => $grandChild->name(),
		'home' => $grandChild->home(),
		'depth' => $grandChild->depth(),
		'index' => $grandChild->index(),
		'tree' => implode('/', $grandChild->tree()),

		// Files
		'template' => $grandChild->path(),
		'stylesheets' => $grandChild->stylesheets(),
		'scripts' => $grandChild->scripts(),

		// Other pages
		'children' => $grandChild->listChildren('path'),
		'parents' => $grandChild->listParents(),
		'siblings' => $grandChild->listSiblings(),

	),

);

?>