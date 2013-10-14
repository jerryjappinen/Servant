<?php

// Create classes for body class
$i = 1;
$classes = array();
$tree = $servant->page()->tree();
foreach ($tree as $value) {
	$classes[] = 'page-'.implode('-', array_slice($tree, 0, $i));
	$i++;
}
unset($tree, $i);

// FLAG "language-javascript" really doesn't belong here
$output = '
	<body class="level-'.count($servant->page()->tree()).' index-'.$servant->page()->index().' '.implode(' ', $classes).'">
		';
		unset($classes);

		$output .= '
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
			foreach ($level1 as $key => $page) {

				// Handle list classes
				$classes = array();
				$selected = false;
				$dropdown = false;

				// Selected
				if ($servant->page()->tree(0) === $key) {
					$selected = true;
					$classes[] = 'active';
				}

				// Children
				if (is_array($page)) {
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
					$output .= '<a href="'.$page->readPath('domain').'/">'.$page->name($key).'</a>';
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
			$level2 = $servant->pages()->map($servant->page()->tree(0));
			if (is_array($level2)) {
				$output .= '<div id="sidebar"><ul class="menu-2">';

				// List items
				foreach ($level2 as $key => $page) {

					// Link HTML
					$link = '<a href="'.$servant->paths()->root('domain').$servant->site()->id().'/read/'.$servant->page()->tree(0).'/'.$key.'/">'.$servant->format()->title($key).'</a>';

					// Selected item
					if ($servant->page()->tree(1) === $key) {
						$output .= '<li class="selected"><strong>'.$link.'</strong>';
						unset($link);

						// Possible children
						if (is_array($page)) {
							$output .= '<ul class="menu-3">';

							// Child pages in array
							$skip = true;
							foreach ($page as $key2 => $page2) {

								// Skip first
								if ($skip) {
									$skip = false;
									continue;
								}

								// Child item HTML
								$link = '<a href="'.$servant->paths()->root('domain').$servant->site()->id().'/read/'.$servant->page()->tree(0).'/'.$key.'/'.$key2.'/">'.$servant->format()->title($key2).'</a>';
								if ($servant->page()->tree(2) === $key2) {
									$output .= '<li class="selected"><strong>'.$link.'</strong></li>';
								} else {
									$output .= '<li>'.$link.'</li>';
								}
							}
							unset($skip, $level3, $key2, $page2);

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
			$output .= $servant->template()->content();

			$output .= '
		</div>
';

echo $output;
?>