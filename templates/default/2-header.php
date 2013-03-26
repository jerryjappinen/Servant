<?php

// Header
$output = '
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



	// Dropdown menu
	$output .= '<select class="menu-1 menu-2 menu-1-2" onchange="window.open(this.options[this.selectedIndex].value,\'_top\')">';
	foreach ($servant->site()->articles() as $key => $value) {

		// First-level article
		$output .=
		'<option value="'.$servant->paths()->root('domain').$servant->site()->id().'/read/'.$key.'/" '.($key === $servant->article()->tree(0) ? ' selected' : '').'>'.
		$servant->format()->name($key).
		'</option>';

	}
	$output .= '</select>';
	unset($key, $value);
	$output .= '<div class="clear"></div>';



// Body content
$output .= '</div>';

echo $output;
?>