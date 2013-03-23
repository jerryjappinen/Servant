<?php

// Footer
echo '<div class="footer container">';

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
		echo '<div class="row"><div class="span3"><ul class="nav nav-list"><li class="list-header">'.$servant->site()->name().'</li><li><a href="'.$servant->paths()->root('domain').$servant->site()->id().'/sitemap/'.'">Sitemap</a></li>';

		// Create footer links for articles
		foreach ($pages as $id) {
			echo '<li><a href=".">'.$servant->format()->name($id).'</a></li>';
		}
		echo '</ul></div>';

		// Create footer links for categories
		$i = 2;
		foreach ($categories as $id) {
			echo $i === 1 ? '<div class="row">' : '';
			echo '<div class="span3"><ul class="nav nav-list"><li class="list-header">'.$servant->format()->name($id).'</li>';
			foreach ($servant->site()->articles($id) as $key => $value) {
				echo '<li><a href=".">'.$servant->format()->name($key).'</a></li>';
			}
			echo '</ul></div>';

			// Handle counter
			if ($i === 4) {
				echo '</div>';
				$i = 1;
			} else {
				$i++;
			}
		}

		// Debug stuff
		// echo htmlDump($servant->site()->dump());

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