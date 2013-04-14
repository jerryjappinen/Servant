<?php

// Header
$output = '<div class="frame-header"><div class="frame-container">
	<h1><a href="'.$servant->paths()->root('domain').$servant->site()->id().'/read/'.'">'.$servant->site()->name().'</a></h1>';



	// Level 1 menu
	$level1 = $servant->site()->articles();
	if (count($level1) > 1) {
		$output .= '<div id="responsive-menu"><ul class="menu-1">';
		foreach ($level1 as $key => $value) {

			// Selected
			if ($servant->article()->tree(0) === $key) {
				$output .= '<li><strong><a href="'.$servant->paths()->root('domain').$servant->site()->id().'/read/'.$key.'/">'.$servant->format()->name($key).'</a></strong></li>';

			// Normal link
			} else {
				$output .= '<li><a href="'.$servant->paths()->root('domain').$servant->site()->id().'/read/'.$key.'/">'.$servant->format()->name($key).'</a></li>';
			}

		}
		$output .= '</ul></div>';
	}
	unset($level1, $key, $value);



	// // Submenu in a sidebar
	// $level2 = $servant->site()->articles($servant->article()->tree(0));
	// if (is_array($level2)) {
	// 	$output .= '<div id="responsive-menu"><ul class="menu-2">';

	// 	// List items
	// 	foreach ($level2 as $key => $value) {

	// 		// Link HTML
	// 		$link = '<a href="'.$servant->paths()->root('domain').$servant->site()->id().'/read/'.$servant->article()->tree(0).'/'.$key.'/">'.$servant->format()->name($key).'</a>';

	// 		// Selected item or group
	// 		if ($servant->article()->tree(1) === $key) {
	// 			$output .= '<li class="selected">';

	// 			// This specific link is selected
	// 			if ($servant->article()->index() === 0 or $servant->article()->level() === 2) {
	// 				$output .= '<strong>'.$link.'</strong>';
	// 			} else {
	// 				$output .= $link;
	// 			}

	// 			unset($link);

	// 		// Link only
	// 		} else {
	// 			$output .= '<li>'.$link;
	// 		}
	// 		unset($link);

	// 		// Possible children
	// 		if (is_array($value)) {
	// 			$output .= '<ul class="menu-3">';

	// 			// Child pages in array
	// 			$skip = true;
	// 			foreach ($value as $key2 => $value2) {

	// 				// Skip first
	// 				if ($skip) {
	// 					$skip = false;
	// 					continue;
	// 				}

	// 				// Child item HTML
	// 				$link = '<a href="'.$servant->paths()->root('domain').$servant->site()->id().'/read/'.$servant->article()->tree(0).'/'.$key.'/'.$key2.'/">'.$servant->format()->name($key2).'</a>';
	// 				if ($servant->article()->tree(2) === $key2) {
	// 					$output .= '<li class="selected"><strong>'.$link.'</strong></li>';
	// 				} else {
	// 					$output .= '<li>'.$link.'</li>';
	// 				}
	// 			}
	// 			unset($skip, $level3, $key2, $value2);

	// 			$output .= '</ul>';
	// 		}
	// 		$output .= '</li>';

	// 	}

	// 	$output .= '</ul></div>';
	// }
	// unset($level2, $key, $value);



	// // Dropdown menu
	// $output .= '<select class="menu-1 menu-2 menu-1-2" onchange="window.open(this.options[this.selectedIndex].value,\'_top\')">';
	// foreach ($servant->site()->articles() as $key => $value) {

	// 	// First-level article
	// 	$output .=
	// 	'<option value="'.$servant->paths()->root('domain').$servant->site()->id().'/read/'.$key.'/" '.($key === $servant->article()->tree(0) ? ' selected' : '').'>'.
	// 	$servant->format()->name($key).
	// 	'</option>';

	// }
	// $output .= '</select>';
	// unset($key, $value);
	// $output .= '<div class="clear"></div>';



// End header
$output .= '<div class="clear"></div></div></div>';

echo $output;
?>