<?php

// Body content
$output = '<div class="frame-body"><div class="frame-container">';

	// FLAG I really shouldn't hardcode the name of read action...
	if ($servant->action()->id() === 'read') {

		// Submenu in a sidebar
		$level2 = $servant->site()->pages($servant->page()->tree(0));
		if (is_array($level2)) {
			$output .= '<div class="frame-sidebar"><ul class="menu-2">';

			// List items
			foreach ($level2 as $key => $value) {

				// Link HTML
				$link = '<a href="'.$servant->paths()->userAction('read', 'domain', $servant->page()->tree(0), $key).'">'.$servant->format()->pageName($key).'</a>';

				// Selected item or group
				if ($servant->page()->tree(1) === $key) {
					$output .= '<li class="selected">';

					// This specific link is selected
					if ($servant->page()->level() === 2) {
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
						$link = '<a href="'.$servant->paths()->userAction('read', 'domain', $servant->page()->tree(0), $key, $key2).'">'.$servant->format()->pageName($key2).'</a>';
						if ($servant->page()->tree(1) === $key and $servant->page()->tree(2) === $key2) {
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

	}



	// Content
	$output .= '<div class="frame-page">'.

	// Debug
	html_dump($servant->pages()->map('technical-docs', 'theme-packages', 'how-themes-work')->dump()).
	// html_dump($servant->pages()->files()).

	$servant->template()->content().'</div>';



// End body
$output .= '<div class="clear"></div></div></div>';

echo $output;
?>