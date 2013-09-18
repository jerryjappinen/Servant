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



// End header
$output .= '<div class="clear"></div></div></div>';

echo $output;
?>