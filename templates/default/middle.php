<?php

$output = '
	<body class="'.implode(' ', $servant->article()->tree()).'">

		<div id="mainmenu">

			<div class="buffer first">
				<div class="contentarea">
					';

					// Level 1 menu
					$level1 = $servant->site()->articles();
					if (!empty($level1)) {
						$output .= '<ol class="plain push-left block reset">';
						foreach ($level1 as $key => $value) {
							$output .= '<li class="reset'.($servant->article()->tree(0) === $key ? ' selected': '').'"><a href="'.$servant->paths()->root('domain').$servant->site()->id().'/'.$key.'/">'.$servant->format()->name($key).'</a></li>';
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

					// Additional menu items
					$output .= '
					<ul class="plain push-right block reset">
						<li><a href="http://eiskis.net/proot/">Proot home</a></li>
					</ul>
					';

					$output .= '
					<div class="clear"></div>
				</div>
			</div>
			';

			$output .= '
			<div class="buffer second">
				<div class="contentarea">
					';

					// Level 2 menu
					$level2 = $servant->site()->articles($servant->article()->tree(0));
					if (!empty($level2) and is_array($level2)) {

						// List
						$output .= '<ol class="plain push-left block reset">';

						// List items
						foreach ($level2 as $key => $value) {
							$output .= '<li class="reset'.($servant->article()->tree(1) === $key ? ' selected': '').'"><a href="'.$servant->paths()->root('domain').$servant->site()->id().'/'.$servant->article()->tree(0).'/'.$key.'/">'.$servant->format()->name($key).'</a></li>';
						}

						// List ends
						$output .= '</ol>';
					}
					unset($level2, $key, $value);

					$output .= '
					<div class="clear"></div>
				</div>
			</div>

			<div class="clear"></div>
		</div>
		';

		$output .= '
		<div class="buffer" id="content">
			<div class="contentarea">
				';

				// One-column layout
				$current = count($servant->article()->tree())-1;
				if ($current < 2) {
					// Generate category or article title
					// FLAG really need article object: check for categories, first title is category name
					if (false) {
						$title = $servant->format()->name($servant->article()->tree($current-1));
					} else {
						$title = $servant->article()->name();
					}

					$output .= '
					<h1 class="close-top">'.$title.'</h1>
					'.$servant->article()->extract();

				// Two-column layout
				} else {
					$output .= '
					<div class="column first nine">
						<h1 class="close-top">'.$servant->format()->name($servant->article()->tree($current-1)).': '.$servant->article()->name().'</h1>
						'.$servant->article()->extract().'
					</div>
					<div class="column last three" id="submenu">

						<div class"buffer">
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

					</div>
					<div class="clear"></div>
					';
				}

				$output .= '
				<h4><strong>Developer stuff</strong></h4>
				'.htmlDump($servant->site()->articles()).'
				<div class="clear"></div>
			</div>
		</div>
';

echo $output;
?>