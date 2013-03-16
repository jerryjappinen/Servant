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
			<h1><a href="'.$servant->paths()->root('domain').$servant->site()->id().'/'.$servant->action()->id().'/'.'">'.$servant->site()->name().'</a></h1>
			';

			// Level 1 menu
			$level1 = $servant->site()->articles();
			if (count($level1) > 1) {
				$output .= '<ol class="menu-1">';
				foreach ($level1 as $key => $value) {

					// Selected
					if ($servant->article()->tree(0) === $key) {
						$output .= '<li><strong><a href="'.$servant->paths()->root('domain').$servant->site()->id().'/'.$servant->action()->id().'/'.$key.'/">'.$servant->format()->name($key).'</a></strong></li>';

					// Normal link
					} else {
						$output .= '<li><a href="'.$servant->paths()->root('domain').$servant->site()->id().'/'.$servant->action()->id().'/'.$key.'/">'.$servant->format()->name($key).'</a></li>';
					}

				}
				$output .= '</ol>';
			}
			unset($level1, $key, $value);

			// Two-level dropdown menu
			$output .= '<select class="menu-1 menu-2 menu-1-2" onchange="window.open(this.options[this.selectedIndex].value,\'_top\')">';
			foreach ($servant->site()->articles() as $key => $value) {

				// First-level article
				if (is_string($value) or is_array($value) and count($value) === 1) {
					$output .= '<option value="'.$servant->paths()->root('domain').$servant->site()->id().'/'.$servant->action()->id().'/'.$key.'/">'.$servant->format()->name($key).'</option>';

				// Nested
				} else if (is_array($value)) {

					// Wrap in optgroup
					$output .= '<optgroup label="'.$servant->format()->name($key).'">';
					foreach ($value as $key2 => $value2) {
						$output .= '<option value="'.$servant->paths()->root('domain').$servant->site()->id().'/'.$servant->action()->id().'/'.$key.'/'.$key2.'/">'.$servant->format()->name($key2).'</option>';
					}
					$output .= '</optgroup>';

				}

			}
			$output .= '</select>';
			unset($key, $value);
			$output .= '<div class="clear"></div>';

			$output .= '
		</div>
		';

		// Body content
		$output .= '
		<div id="body">
			';

			// Level 2 menu
			$level2 = $servant->site()->articles($servant->article()->tree(0));
			if (count($level2) > 1 and is_array($level2)) {

				// List
				$output .= '
					<ol class="menu-2">
					';

						// List items
						foreach ($level2 as $key => $value) {

							// Selected
							if ($servant->article()->tree(1) === $key) {
								$output .= '<li><strong><a href="'.$servant->paths()->root('domain').$servant->site()->id().'/'.$servant->action()->id().'/'.$servant->article()->tree(0).'/'.$key.'/">'.$servant->format()->name($key).'</a></strong></li>';

							// Normal link
							} else {
								$output .= '<li><a href="'.$servant->paths()->root('domain').$servant->site()->id().'/'.$servant->action()->id().'/'.$servant->article()->tree(0).'/'.$key.'/">'.$servant->format()->name($key).'</a></li>';
							}

						}

					// List ends
					$output .= '
					</ol>
					<div class="clear"></div>
				';

			}
			unset($level2, $key, $value);

			// Body content
			$output .= $servant->action()->content();

			// Sidebar if needed
			if (count($servant->article()->tree())-1 >= 2) {
				$output .= '
				<div id="sidebar">

					<ol>
					';

					// Level 3 menu
					$level3 = $servant->site()->articles($servant->article()->tree(0), $servant->article()->tree(1));
					if (!empty($level3) and is_array($level3)) {
						foreach ($level3 as $key => $value) {
							$output .= '<li class="'.($servant->article()->tree(2) === $key ? ' selected': '').'"><a href="'.$servant->paths()->root('domain').$servant->site()->id().'/'.$servant->action()->id().'/'.$servant->article()->tree(0).'/'.$servant->article()->tree(1).'/'.$key.'/">'.$servant->format()->name($key).'</a></li>';
						}
					}
					unset($level3, $key, $value);

					$output .= '
					</ol>

				</div>
				<div class="clear"></div>
				';
			}

			$output .= '
			<h4><strong>Developer stuff</strong></h4>
			'.htmlDump($servant->response()->headers()).'
			<div class="clear"></div>
		</div>
';

echo $output;
?>