<?php

$output = '
<div class="navbar navbar-inverse navbar-fixed-top">
	<div class="navbar-inner">
		<div class="container">

            <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>

			<a href="'.$servant->paths()->root('domain').'" class="brand">'.$servant->site()->name().'</a>
			';



// Level 1 menu
$level1 = $servant->pages()->map();
if (count($level1) > 1) {
	$output .= '<div class="nav-collapse collapse"><ul class="nav">';
	foreach ($level1 as $key => $tempPage) {

		// Handle list classes
		$classes = array();
		$selected = false;
		$dropdown = false;

		// Selected
		if ($page->tree(0) === $key) {
			$selected = true;
			$classes[] = 'active';
		}

		// Children
		if (is_array($tempPage)) {
			$dropdown = true;
			$classes[] = 'dropdown';
		}

		// List item
		$output .= '<li'.(!empty($classes) ? ' class="'.implode($classes).'"' : '').'>';

		// Dropdown menu
		if ($dropdown) {
			$output .= '<a href="." class="dropdown-toggle" data-toggle="dropdown">'.$key.' <b class="caret"></b></a>
				<ul class="dropdown-menu">
					<li><a href="#">Action</a></li>
					<li><a href="#">Another action</a></li>
					<li><a href="#">Something else here</a></li>
					<li class="divider"></li>
					<li class="nav-header">Nav header</li>
					<li><a href="#">Separated link</a></li>
					<li><a href="#">One more separated link</a></li>
				</ul>';

		// Link
		} else {
			$output .= '<a href="'.$tempPage->readPath('domain').'/">'.$tempPage->name($key).'</a>';
		}

		$output .= '</li>';

		unset($selected, $dropdown);
	}
	$output .= '</ul></div>';
}
unset($level1, $key, $value);


// End header
$output .= '
			</div>
		</div>
	</div>
';



// Body content
$output .= '
<div class="container">
	';



	// Submenu in a sidebar
	$level2 = $servant->pages()->map($page->tree(0));
	if (is_array($level2)) {
		$output .= '<div id="sidebar"><ul class="menu-2">';

		// List items
		foreach ($level2 as $key => $tempPage) {

			// Link HTML
			$link = '<a href="'.$servant->paths()->root('domain').$servant->site()->id().'/read/'.$page->tree(0).'/'.$key.'/">'.$servant->format()->title($key).'</a>';

			// Selected item
			if ($page->tree(1) === $key) {
				$output .= '<li class="selected"><strong>'.$link.'</strong>';
				unset($link);

				// Possible children
				if (is_array($tempPage)) {
					$output .= '<ul class="menu-3">';

					// Child pages in array
					$skip = true;
					foreach ($tempPage as $key2 => $tempPage2) {

						// Skip first
						if ($skip) {
							$skip = false;
							continue;
						}

						// Child item HTML
						$link = '<a href="'.$servant->paths()->root('domain').$servant->site()->id().'/read/'.$page->tree(0).'/'.$key.'/'.$key2.'/">'.$servant->format()->title($key2).'</a>';
						if ($page->tree(2) === $key2) {
							$output .= '<li class="selected"><strong>'.$link.'</strong></li>';
						} else {
							$output .= '<li>'.$link.'</li>';
						}
					}
					unset($skip, $level3, $key2, $tempPage2);

					$output .= '</ul>';
				}
				$output .= '</li>';

			// Link only
			} else {
				$output .= '<li>'.$link.'</li>';
			}
			unset($link);

		}

		$output .= '</ul></div>';
	}
	unset($level2, $key, $value);



	// Body content
	$output .= $template->content();

	$output .= '
</div>
';

// Footer
$output .= '<div class="footer container">';

		// Sort pages into pages and categories
		$tempPages = array();
		$categories = array();
		foreach ($servant->pages()->map() as $key => $tempPage) {
			if (is_array($tempPage)) {
				$categories[] = $key;
			} else {
				$tempPages[] = $key;
			}
		}

		// Pages & generic stuff
		$output .= '<div class="row"><div class="span3"><ul class="nav nav-list"><li class="list-header">'.$servant->site()->name().'</li><li><a href="'.$servant->paths()->endpoint('sitemap', 'domain', $page->tree()).'">Sitemap</a></li>';

		// Create footer links for pages
		foreach ($tempPages as $id) {
			$output .= '<li><a href=".">'.$servant->format()->title($id).'</a></li>';
		}
		$output .= '</ul></div>';

		// Create footer links for categories
		$i = 2;
		foreach ($categories as $id) {
			$output .= $i === 1 ? '<div class="row">' : '';
			$output .= '<div class="span3"><ul class="nav nav-list"><li class="list-header">'.$servant->format()->title($id).'</li>';
			foreach ($servant->pages()->map($id) as $key => $tempPage) {
				$output .= '<li><a href=".">'.$key.'</a></li>';
			}
			$output .= '</ul></div>';

			// Handle counter
			if ($i === 4) {
				$output .= '</div>';
				$i = 1;
			} else {
				$i++;
			}
		}

$output .= '</div>';



// Output via the default template
echo $template->nest('html', $output);
?>