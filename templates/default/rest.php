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
		echo '<h2>'.$servant->site()->name().'</h2>
		<ul>
			<li><a href="'.$servant->paths()->root('domain').$servant->site()->id().'/sitemap/'.'">Sitemap</a></li>
		';

		// Create footer links for articles
		foreach ($pages as $id) {
			echo '<li><a href=".">'.$servant->format()->name($id).'</a></li>';
		}
		echo '</ul>';

		// Create footer links for categories
		foreach ($categories as $id) {
			echo '<h2>'.$servant->format()->name($id).'</h2><ul>';
			foreach ($servant->site()->articles($id) as $key => $value) {
				echo '<li><a href=".">'.$servant->format()->name($key).'</a></li>';
			}
			echo '</ul>';
		}
		echo '<div class="clear"></div>';

		// Debug stuff
		// echo htmlDump($servant->site()->articles());

echo '</div>';



// Scripts
$scripts = $servant->theme()->scripts();
if (!empty($scripts)) {
	echo '<script src="'.$servant->paths()->root('domain').$servant->site()->id().'/scripts/'.'"></script>';
}
unset($scripts);

// End it all
echo '
	</body>
</html>
';

?>