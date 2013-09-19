<?php

// Footer
echo '<div class="frame-footer"><div class="frame-container">';

	// Sort articles into pages and categories
	$pages = array();
	$categories = array();
	foreach ($servant->site()->articles() as $key => $value) {
		if (is_array($value)) {
			$categories[] = $key;
		} else if (is_string($value)) {
			$pages[] = $key;
		}
	}

	// Pages & generic stuff
	echo '<dl><dt><a href="'.$servant->paths()->root('domain').'">'.$servant->site()->name().'</a></dt><dd><a href="'.$servant->paths()->root('domain').'sitemap/'.implode('/', $servant->site()->article()->tree()).'/'.'">Sitemap</a></dd>
	';

	// Create footer links for articles
	foreach ($pages as $id) {
		echo '<dd><a href="'.$servant->paths()->root('domain').'read/'.$id.'/">'.$servant->format()->name($id).'</a></dd>';
	}
	echo '</dl>';

	// Create footer links for categories
	foreach ($categories as $category) {
		echo '<dl><dt><a href="'.$servant->paths()->root('domain').'read/'.$category.'/">'.$servant->format()->name($category).'</a></dt>';
		foreach ($servant->site()->articles($category) as $id => $value) {
			echo '<dd><a href="'.$servant->paths()->root('domain').'read/'.$category.'/'.$id.'/">'.$servant->format()->name($id).'</a></dd>';
		}
		echo '</dl>';
	}
	echo '<div class="clear"></div>';

	// Debug stuff
	// echo html_dump($servant->utilities()->available());

// Close footer
echo '</div></div><div class="clear"></div>';

?>