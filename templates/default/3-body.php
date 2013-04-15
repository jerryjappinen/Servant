<?php

// Body content
$output = '<div class="frame-body"><div class="frame-container">';



	// Submenu in a sidebar
	$level2 = $servant->site()->articles($servant->article()->tree(0));
	if (is_array($level2)) {
		$output .= '<div class="frame-sidebar"><ul class="menu-2">';

		// List items
		foreach ($level2 as $key => $value) {

			// Link HTML
			$link = '<a href="'.$servant->paths()->root('domain').$servant->site()->id().'/read/'.$servant->article()->tree(0).'/'.$key.'/">'.$servant->format()->name($key).'</a>';

			// Selected item or group
			if ($servant->article()->tree(1) === $key) {
				$output .= '<li class="selected">';

				// This specific link is selected
				if ($servant->article()->level() === 2) {
					$output .= '<strong>'.$link.'</strong>';
				} else {
					$output .= $link;
				}

				unset($link);

			// Link only
			} else {
				$output .= '<li>'.$link;
			}
			unset($link);

			// Possible children
			if (is_array($value)) {
				$output .= '<ul class="menu-3">';

				// Child pages in array
				foreach ($value as $key2 => $value2) {

					// Child item HTML
					$link = '<a href="'.$servant->paths()->root('domain').$servant->site()->id().'/read/'.$servant->article()->tree(0).'/'.$key.'/'.$key2.'/">'.$servant->format()->name($key2).'</a>';
					if ($servant->article()->tree(1) === $key and $servant->article()->tree(2) === $key2) {
						$output .= '<li class="selected"><strong>'.$link.'</strong></li>';
					} else {
						$output .= '<li>'.$link.'</li>';
					}
				}
				unset($level3, $key2, $value2);

				$output .= '</ul>';
			}
			$output .= '</li>';

		}

		$output .= '</ul></div>';
	}
	unset($level2, $key, $value);



	// Article content
	$output .= '<div class="frame-article">'.$servant->template()->content().'</div>';



// End body
$output .= '<div class="clear"></div></div></div>';

echo $output;
?>