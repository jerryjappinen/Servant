<?php

// Footer
echo '<div id="footer">';

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
	echo '<dl><dt>'.$servant->site()->name().'</dt><dd><a href="'.$servant->paths()->root('domain').$servant->site()->id().'/sitemap/'.implode('/', $servant->site()->article()->tree()).'/'.'">Sitemap</a></dd>
	';

	// Create footer links for articles
	foreach ($pages as $id) {
		echo '<dd><a href=".">'.$servant->format()->name($id).'</a></dd>';
	}
	echo '</dl>';

	// Create footer links for categories
	foreach ($categories as $id) {
		echo '<dl><dt>'.$servant->format()->name($id).'</dt>';
		foreach ($servant->site()->articles($id) as $key => $value) {
			echo '<dd><a href=".">'.$servant->format()->name($key).'</a></dd>';
		}
		echo '</dl>';
	}
	echo '<div class="clear"></div>';

	// Debug stuff
	echo htmlDump($servant->site()->configuration());

// Close footer
echo '</div>';

?>