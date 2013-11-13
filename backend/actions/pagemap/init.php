<?php

// Root page
$root = create_object(new ServantSitemap($servant))->init()->root();

// Foo
$output = array();
foreach ($root->children() as $page) {
	if ($page->children()) {
		$output[$page->id()] = $page->listChildren('name');
	} else {
		$output[$page->id()] = implode('/', $page->tree());
	}
}

?>