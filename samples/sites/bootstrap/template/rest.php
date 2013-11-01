<?php

// Footer
echo '<div class="footer container">';

		// Sort pages into pages and categories
		$pages = array();
		$categories = array();
		foreach ($servant->pages()->map() as $key => $page) {
			if (is_array($page)) {
				$categories[] = $key;
			} else {
				$pages[] = $key;
			}
		}

		// Pages & generic stuff
		echo '<div class="row"><div class="span3"><ul class="nav nav-list"><li class="list-header">'.$servant->site()->name().'</li><li><a href="'.$servant->paths()->userAction('sitemap', 'domain', $servant->page()->tree()).'">Sitemap</a></li>';

		// Create footer links for pages
		foreach ($pages as $id) {
			echo '<li><a href=".">'.$servant->format()->title($id).'</a></li>';
		}
		echo '</ul></div>';

		// Create footer links for categories
		$i = 2;
		foreach ($categories as $id) {
			echo $i === 1 ? '<div class="row">' : '';
			echo '<div class="span3"><ul class="nav nav-list"><li class="list-header">'.$servant->format()->title($id).'</li>';
			foreach ($servant->pages()->map($id) as $key => $page) {
				echo '<li><a href=".">'.$key.'</a></li>';
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

echo '</div>';

// Include scripts
// Path to scripts action
$tree = array();
if ($servant->action()->isRead()) {
	$tree = $servant->page()->tree();
}
echo '<script src="'.$servant->paths()->userAction('scripts', 'domain', $tree).'"></script>';

// End it all
echo '
	</body>
</html>
';

?>