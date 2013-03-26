<?php

// Body content
$output = '<div id="body">';



	// Submenu in a sidebar
	$level2 = $servant->site()->articles($servant->article()->tree(0));
	if (is_array($level2)) {
		$output .= '<div id="sidebar"><ul class="menu-2">';

		// List items
		foreach ($level2 as $key => $value) {

			// Link HTML
			$link = '<a href="'.$servant->paths()->root('domain').$servant->site()->id().'/read/'.$servant->article()->tree(0).'/'.$key.'/">'.$servant->format()->name($key).'</a>';

			// Selected item
			if ($servant->article()->tree(1) === $key) {
				$output .= '<li class="selected"><strong>'.$link.'</strong>';
				unset($link);

				// Possible children
				if (is_array($value)) {
					$output .= '<ul class="menu-3">';

					// Child pages in array
					$skip = true;
					foreach ($value as $key2 => $value2) {

						// Skip first
						if ($skip) {
							$skip = false;
							continue;
						}

						// Child item HTML
						$link = '<a href="'.$servant->paths()->root('domain').$servant->site()->id().'/read/'.$servant->article()->tree(0).'/'.$key.'/'.$key2.'/">'.$servant->format()->name($key2).'</a>';
						if ($servant->article()->tree(2) === $key2) {
							$output .= '<li class="selected"><strong>'.$link.'</strong></li>';
						} else {
							$output .= '<li>'.$link.'</li>';
						}
					}
					unset($skip, $level3, $key2, $value2);

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



	// Article content
	$output .= '<div id="article">'.$servant->template()->content().'</div><div class="clear"></div>';



// End body
$output .= '</div>';

echo $output;
?>