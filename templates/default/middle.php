<?php

// Create classes for body class
$i = 1;
$classes = array();
$tree = $servant->article()->tree();
foreach ($tree as $value) {
	$classes[] = 'article-'.implode('-', array_slice($tree, 0, $i));
	$i++;
}
unset($tree, $i);

$output = '
	<body class="level-'.count($servant->article()->tree()).' '.implode(' ', $classes).'">
		';
		unset($classes);

		$output .= '
		<div id="header">
			<h1><a href="'.$servant->paths()->root('domain').$servant->site()->id().'/read/'.'">'.$servant->site()->name().'</a></h1>
			';



			// Level 1 menu
			$level1 = $servant->site()->articles();
			if (count($level1) > 1) {
				$output .= '<ul class="menu-1">';
				foreach ($level1 as $key => $value) {

					// Selected
					if ($servant->article()->tree(0) === $key) {
						$output .= '<li><strong><a href="'.$servant->paths()->root('domain').$servant->site()->id().'/read/'.$key.'/">'.$servant->format()->name($key).'</a></strong></li>';

					// Normal link
					} else {
						$output .= '<li><a href="'.$servant->paths()->root('domain').$servant->site()->id().'/read/'.$key.'/">'.$servant->format()->name($key).'</a></li>';
					}

				}
				$output .= '</ul>';
			}
			unset($level1, $key, $value);



			// Two-level dropdown menu
			$output .= '<select class="menu-1 menu-2 menu-1-2" onchange="window.open(this.options[this.selectedIndex].value,\'_top\')">';
			foreach ($servant->site()->articles() as $key => $value) {

				// First-level article
				$output .= '<option value="'.
				$servant->paths()->root('domain').$servant->site()->id().
				'/read/'.$key.'/"'.
				($key === $servant->article()->tree(0) ? ' selected' : '').
				'>'.$servant->format()->name($key).'</option>';

				// Nested
				// } else if (is_array($value)) {

				// 	// Wrap in optgroup
				// 	$output .= '<optgroup label="'.$servant->format()->name($key).'">';
				// 	foreach ($value as $key2 => $value2) {
				// 		$output .= '<option value="'.$servant->paths()->root('domain').$servant->site()->id().'/read/'.$key.'/'.$key2.'/"'.($key == $servant->article()->parents(0) and $key2 == $servant->article()->id() ? '' : ' selected').'>'.$servant->format()->name($key2).'</option>';
				// 	}
				// 	$output .= '</optgroup>';

				// }

			}
			$output .= '</select>';
			unset($key, $value);
			$output .= '<div class="clear"></div>';



			// Body content
		$output .= '
		</div>
		<div id="body">
			';



			// Level 2 menu
			$homePage = array_keys($servant->site()->articles());
			$homePage = $homePage[0];
			$level2 = $servant->site()->articles($servant->article()->tree(0));
			if ((is_array($level2) and count($level2) > 1) or true) {

				// Menu items
				if (is_array($level2) and count($level2) > 1) {

					// List
					$output .= '<ul class="menu-2">';
					foreach ($level2 as $key => $value) {

						// Selected
						if ($servant->article()->tree(1) === $key) {
							$output .= '<li><strong><a href="'.$servant->paths()->root('domain').$servant->site()->id().'/read/'.$servant->article()->tree(0).'/'.$key.'/">'.$servant->format()->name($key).'</a></strong></li>';

						// Normal link
						} else {
							$output .= '<li><a href="'.$servant->paths()->root('domain').$servant->site()->id().'/read/'.$servant->article()->tree(0).'/'.$key.'/">'.$servant->format()->name($key).'</a></li>';
						}

					}
					$output .= '</ul><div class="clear"></div>';

				// Show current article if we're not on the home page
				// } else if (!in_array($homePage, array($servant->article()->id(), $servant->article()->tree(0)))) {
				// 	$key = $servant->article()->tree(0);
				// 	$output .= '<ul class="menu-2"><li><strong><a href="'.$servant->paths()->root('domain').$servant->site()->id().'/read/'.$servant->article()->tree(0).'/">'.$servant->format()->name($key).'</a></strong></li></ul><div class="clear"></div>';
				}

			}
			unset($level2, $key, $value);



			// Body content
			$output .= $servant->template()->content();



			// Sidebar if needed
			if (count($servant->article()->tree())-1 >= 2) {
				$output .= '
				<div id="sidebar">

					<ul>
					';

					// Level 3 menu
					$level3 = $servant->site()->articles($servant->article()->tree(0), $servant->article()->tree(1));
					if (is_array($level3) and count($level3) > 1) {
						foreach ($level3 as $key => $value) {
							$output .= '<li class="'.($servant->article()->tree(2) === $key ? ' selected': '').'"><a href="'.$servant->paths()->root('domain').$servant->site()->id().'/read/'.$servant->article()->tree(0).'/'.$servant->article()->tree(1).'/'.$key.'/">'.$servant->format()->name($key).'</a></li>';
						}
					}
					unset($level3, $key, $value);

					$output .= '
					</ul>

				</div>
				<div class="clear"></div>
				';
			}

			$output .= '
		</div>
';

echo $output;
?>