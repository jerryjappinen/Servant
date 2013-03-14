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
	<body class="language-javascript '.implode(' ', $classes).'">
		';
		unset($classes);

		$output .= '
		<div class="dark" id="menu">

			<div class="buffer first">
				<div class="contentarea">
					<ol class="plain push-left block reset">
					';

					// Level 1 menu
					$level1 = $servant->site()->articles();
					if (!empty($level1)) {
						foreach ($level1 as $key => $value) {
							$output .= '<li class="reset'.($servant->article()->tree(0) === $key ? ' selected': '').'"><a href="'.$servant->paths()->root('domain').$servant->site()->id().'/'.$servant->action()->id().'/'.$key.'/">'.$servant->format()->name($key).'</a></li>';
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
					$level2 = $servant->site()->articles($servant->article()->tree(0));
					if (!empty($level2) and is_array($level2)) {
						foreach ($level2 as $key => $value) {
							$output .= '<li class="reset'.($servant->article()->tree(1) === $key ? ' selected': '').'"><a href="'.$servant->paths()->root('domain').$servant->site()->id().'/'.$servant->action()->id().'/'.$servant->article()->tree(0).'/'.$key.'/">'.$servant->format()->name($key).'</a></li>';
						}
					}
					unset($level2, $key, $value);

					$output .= '
					</ol>
					';

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
					<div class="clear"></div>
				</div>
			</div>

			<div class="clear"></div>
		</div>



		<div class="buffer" id="content">
			<div class="contentarea">
				';

				// One-column layout
				$current = count($servant->article()->tree())-1;
				if ($current < 2) {
					$output .= $servant->action()->content();

				// Two-column layout
				} else {
					$output .= '
					<div class="column first nine">
						'.$servant->action()->content().'
					</div>
					<div class="column last three" id="submenu">

						<div class"buffer">
							<ol class="reset">
							';

							// Level 3 menu
							$level3 = $servant->site()->articles($servant->article()->tree(0), $servant->article()->tree(1));
							if (!empty($level3) and is_array($level3)) {
								foreach ($level3 as $key => $value) {
									$output .= '<li class="reset'.($servant->article()->tree(2) === $key ? ' selected': '').'"><a href="'.$servant->paths()->root('domain').$servant->site()->id().'/'.$servant->action()->id().'/'.$servant->article()->tree(0).'/'.$servant->article()->tree(1).'/'.$key.'/">'.$servant->format()->name($key).'</a></li>';
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
				'.htmlDump($servant->settings()->defaults()).'
				<div class="clear"></div>
			</div>
		</div>
';

echo $output;
?>