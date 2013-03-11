<?php

$output = '
	<body class="'.implode(' ', $servant->article()->tree()).'">

		<div id="mainmenu">

			<div class="first">
				';

				// Level 1 menu
				$level1 = $servant->site()->articles();
				if (count($level1) > 1) {
					$output .= '<ol class="plain push-left block reset">';
					foreach ($level1 as $key => $value) {

						// Selected
						if ($servant->article()->tree(0) === $key) {
							$output .= '<li class="selected"><a href="'.$servant->paths()->root('domain').$servant->site()->id().'/'.$key.'/"><strong>'.$servant->format()->name($key).'</strong></a></li>';

						// Normal link
						} else {
							$output .= '<li class="reset"><a href="'.$servant->paths()->root('domain').$servant->site()->id().'/'.$key.'/">'.$servant->format()->name($key).'</a></li>';
						}

					}
					$output .= '</ol>';
				}
				unset($level1, $key, $value);

				// Full dropdown menu
				$output .= '<select class="hidden" onchange="window.open(this.options[this.selectedIndex].value,\'_top\')">';
				foreach ($servant->site()->articles() as $key => $value) {
					$output .= '<optgroup label="'.$servant->format()->name($key).'">';
					$output .= '</optgroup>';
				}
				$output .= '</select>';
				unset($key, $value);

				$output .= '
				<div class="clear"></div>
			</div>
			';

			// Level 2 menu
			$level2 = $servant->site()->articles($servant->article()->tree(0));
			if (count($level2) > 1 and is_array($level2)) {

				// List
				$output .= '
				<div class="second">
					<ol class="plain push-left block reset">
					';

				// List items
				foreach ($level2 as $key => $value) {
					$output .= '<li class="reset'.($servant->article()->tree(1) === $key ? ' selected': '').'"><a href="'.$servant->paths()->root('domain').$servant->site()->id().'/'.$servant->article()->tree(0).'/'.$key.'/">'.$servant->format()->name($key).'</a></li>';
				}

				// List ends
				$output .= '
					</ol>
					<div class="clear"></div>
				</div>
			';

			}
			unset($level2, $key, $value);

			$output .= '
			<div class="clear"></div>
		</div>
		';

		$output .= '
		<div class="buffer" id="content">
			<div class="contentarea">
				';

				// One-column layout
				$currentLevel = count($servant->article()->tree())-1;
				if ($currentLevel < 2) {
					// Generate category or article title
					// FLAG really need article object: check for categories, first title is category name
					if (false) {
						$title = $servant->format()->name($servant->article()->tree($currentLevel-1));
					} else {
						$title = $servant->article()->name();
					}

					$output .= '
					<h1>'.$title.'</h1>
					'.$servant->article()->extract();

				// Two-column layout
				} else {
					$output .= '
					<div class="column first nine">
						<h1>'.$servant->format()->name($servant->article()->tree($currentLevel-1)).': '.$servant->article()->name().'</h1>
						'.$servant->article()->extract().'
					</div>
					<div class="column last three" id="submenu">

						<ol class="reset">
						';

						// Level 3 menu
						$level3 = $servant->site()->articles($servant->article()->tree(0), $servant->article()->tree(1));
						if (!empty($level3) and is_array($level3)) {
							foreach ($level3 as $key => $value) {
								$output .= '<li class="reset'.($servant->article()->tree(2) === $key ? ' selected': '').'"><a href="'.$servant->paths()->root('domain').$servant->site()->id().'/'.$servant->article()->tree(0).'/'.$servant->article()->tree(1).'/'.$key.'/">'.$servant->format()->name($key).'</a></li>';
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
		</div>
';

echo $output;
?>