<?php

// Next and previous links
if ($page->depth() > 0) {

	// Previous
	$previous = $page->previous();
	if ($previous) {
		$prevnext .= '<li class="previous"><a href="'.$previous->endpoint('domain').'">'.$previous->name().'</a></li>';
	}

	// Next
	$next = $page->next();
	if ($next) {
		$prevnext .= '<li class="next"><a href="'.$next->endpoint('domain').'">'.$next->name().'</a></li>';
	}

	$prevnext = $prevnext ? '<div class="prevnext clear-after"><ul class="plain collapse">'.$prevnext.'</ul></div>' : '';
}

?>