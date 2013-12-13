<?php

$content = '';
$max = 3;



// Find blog posts
$category = $page->parents(false, 0);
$posts = array();
foreach (array_reverse($category->children()) as $node) {
	if ($node->page()) {
		$posts[] = $node;
	} else if ($node->category()) {
		foreach (array_reverse($node->children()) as $subnode) {
			if ($subnode->page()) {
				$posts[] = $subnode;
			}
		}
	}
}



// Create posts
$i = 0;
foreach ($posts as $post) {
	if ($i < $max) {
		$content .= '<article class="post">'.$action->nest('page', $servant->create()->input(array('page' => $post->tree())))->output().'</article>';
		$i++;
	}
}
unset($i);



/**
* Full template
*/

$frame = '
<div class="row row-header">
	<div class="row-content buffer clear-after">
		<h1><a href="'.$servant->paths()->root('domain').'">'.$servant->site()->name().'</a></h1>
		'.$mainmenu.'
	</div>
</div>

<div class="row row-body">
	<div class="row-content buffer clear-after">
		<div class="article">
			'.$content.'
		</div>
	</div>
</div>
';

echo $template->nest('html', $frame);
?>