<?php

$output = '
	<body class="'.implode(' ', $servant->site()->selected()).'">

		<div class="dark" id="menu">

			<div class="buffer first">
				<div class="contentarea">
					<ol class="plain push-left block reset">
					';

					// Level 1 menu
					$level1 = $servant->site()->articles();
					if (!empty($level1)) {
						foreach ($level1 as $key => $value) {
							$output .= '<li class="reset'.($servant->site()->selected(0) === $key ? ' selected': '').'"><a href="'.$servant->paths()->root('domain').$servant->site()->id().'/'.$key.'/">'.$servant->format()->name($key).'</a></li>';
						}
					}
					unset($level1, $key, $value);

					$output .= '
					</ol>

					<ul class="plain push-right block reset">
						<li><a href="http://eiskis.net/proot/">Proot home</a></li>
					</ul>

					<div class="clear"></div>
				</div>
			</div>

			<div class="buffer second">
				<div class="contentarea">

					<ol class="plain push-left block reset">
					';

					// Level 2 menu
					$level2 = $servant->site()->articles($servant->site()->selected(0));
					if (!empty($level2) and is_array($level2)) {
						foreach ($level2 as $key => $value) {
							$output .= '<li class="reset'.($servant->site()->selected(1) === $key ? ' selected': '').'"><a href="'.$servant->paths()->root('domain').$servant->site()->id().'/'.$servant->site()->selected(0).'/'.$key.'">'.$servant->format()->name($key).'</a></li>';
						}
					}
					unset($level2, $key, $value);

					$output .= '
					</ol>

					<select class="hidden" onchange="window.open(this.options[this.selectedIndex].value,\'_top\')">
					';

					// Full dropdown menu
					$level1 = $servant->site()->articles();
					if (!empty($level1)) {
						foreach ($level1 as $key => $value) {
							$output .= '<optgroup label="'.$servant->format()->name($key).'">';
							$output .= '</optgroup>';
						}
					}
					unset($level1, $key, $value);

					$output .= '
					</select>

					<div class="clear"></div>
				</div>
			</div>

			<div class="clear"></div>
		</div>



		<div class="buffer" id="content">
			<div class="contentarea">
				';

				// One-column layout
				if (count($servant->site()->selected()) <= 2) {
					$output .= '
					<h1>'.$servant->format()->name(pathinfo($servant->site()->article(), PATHINFO_FILENAME)).'</h1>
					'.$servant->extract()->file($servant->site()->article('server'));

				// Two-column layout
				} else {
					$output .= '
					<div class="column first nine">
						<h1>'.$servant->format()->name(pathinfo($servant->site()->article(), PATHINFO_FILENAME)).'</h1>
						'.$servant->extract()->file($servant->site()->article('server')).'
					</div>
					<div class="column last three" id="submenu">

						<div class"buffer">
							<ol class="reset">
							';

							// Level 3 menu
							$level3 = $servant->site()->articles($servant->site()->selected(0), $servant->site()->selected(1));
							if (!empty($level3) and is_array($level3)) {
								foreach ($level3 as $key => $value) {
									$output .= '<li class="reset'.($servant->site()->selected(1) === $key ? ' selected': '').'"><a href="'.$servant->paths()->root('domain').$servant->site()->id().'/'.$servant->site()->selected(0).'/'.$key.'">'.$servant->format()->name($key).'</a></li>';
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
				'.htmlDump($servant->site()->selected()).'
				<div class="clear"></div>
			</div>
		</div>
';

echo $output;
?>