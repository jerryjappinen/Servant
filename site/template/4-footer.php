<?php

// Footer
echo '<div class="frame-footer"><div class="frame-container">';

	// Sort pages into pages and categories
	$pages = array();
	$categories = array();
	foreach ($servant->site()->pages() as $key => $value) {
		if (is_array($value)) {
			$categories[] = $key;
		} else if (is_string($value)) {
			$pages[] = $key;
		}
	}

	// Pages & generic stuff
	echo '<dl><dt><a href="'.$servant->paths()->root('domain').'">'.$servant->site()->name().'</a></dt><dd><a href="'.$servant->paths()->userAction('sitemap', 'domain', $servant->site()->page()->tree()).'">Sitemap</a></dd>
	';

	// Create footer links for pages
	foreach ($pages as $id) {
		echo '<dd><a href="'.$servant->paths()->userAction('read', 'domain', $id).'">'.$servant->format()->pageName($id).'</a></dd>';
	}
	echo '</dl>';

	// Create footer links for categories
	foreach ($categories as $category) {
		$categoryUrl = $servant->paths()->userAction('read', 'domain', $category);
		echo '<dl><dt><a href="'.$categoryUrl.'">'.$servant->format()->pageName($category).'</a></dt>';
		foreach ($servant->site()->pages($category) as $id => $value) {
			echo '<dd><a href="'.$categoryUrl.$id.'/">'.$servant->format()->pageName($id).'</a></dd>';
		}
		echo '</dl>';
	}
	echo '<div class="clear"></div>';

// Close footer
echo '</div></div><div class="clear"></div>';

?>