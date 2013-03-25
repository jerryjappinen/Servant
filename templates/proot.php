<?php

// HTML headers
$output = '
<!DOCTYPE html>
<html>
	<head>

		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<style type="text/css">@-ms-viewport{width: device-width;}</style>

		<title>'.$servant->site()->name().'</title>
		';

		// Use a favicon if there is one
		if (is_dir($servant->theme()->path('server'))) {
			foreach (rglob_files($servant->theme()->path('server'), 'ico') as $path) {
				$output .= '<link rel="shortcut icon" href="'.$servant->format()->path($path, 'domain', 'server').'" type="image/x-icon">';
				break;
			}
		}

		// Stylesheets
		foreach ($servant->theme()->stylesheets('domain') as $path) {
			$output .= '<link rel="stylesheet" href="'.$path.'" media="screen">';
		}

		$output .= '
	</head>
';



// Create classes for body class
$i = 1;
$classes = array();
$tree = $servant->article()->tree();
foreach ($tree as $value) {
	$classes[] = 'article-'.implode('-', array_slice($tree, 0, $i));
	$i++;
}
unset($tree, $i);

$output .= '
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
					$output .= $servant->template()->content();

				// Two-column layout
				} else {
					$output .= '
					<div class="column first nine">
						'.$servant->template()->content().'
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

// Footer
$output .= '
		<div class="dark buffer" id="footer">
			<div class="contentarea">

				<div class="column six">
					<table>

						<tr>
							<td class="third">Proot home</td>
							<td><a href="http://eiskis.net/proot/" target="_blank">eiskis.net/proot/</a></td>
						</tr>

						<tr>
							<td class="third">Documentation &amp; guides</td>
							<td><a href="http://eiskis.net/proot/guides/" target="_blank">eiskis.net/proot/guides/</a></td>
						</tr>
						<tr>
							<td class="third">Download</td>
							<td><a href="https://bitbucket.org/Eiskis/proot/downloads/proot.zip" target="_blank">Latest release from Bitbucket</a></td>
						</tr>
						<tr>
							<td class="third">Licensed under</td>
							<td><a href="../lgpl.txt" target="_blank">GNU Lesser General Public License</a></td>
						</tr>

					</table>
				</div>

				<div class="column six last">
					<table>

						<tr>
							<td class="third">Development @ Bitbucket</td>
							<td>
								<ul class="plain close">
									<li><a href="https://bitbucket.org/Eiskis/proot/" target="_blank">Project overview</a></li>
									<li><a href="https://bitbucket.org/Eiskis/proot/src" target="_blank">Source code</a></li>
									<li class="last"><a href="https://bitbucket.org/Eiskis/proot/issues?status=new&amp;status=open" target="_blank">Issue management</a></li>
								</ul>
							</td>
						</tr>
						<tr>
							<td class="third">By Jerry Jäppinen</td>
							<td>
								<ul class="plain close">
									<li><a href="https://twitter.com/Eiskis" target="_blank">@Eiskis</a></li>
									<li><a href="http://eiskis.net/" target="_blank">eiskis.net</a></li>
									<li><a href="mailto:eiskis@gmail.com" target="_blank">eiskis@gmail.com</a></li>
									<li class="last"><a href="tel:+358407188776">+358 40 7188776</a></li>
								</ul>
								<div class="clear"></div>
							</td>
						</tr>

					</table>
				</div>

				<div class="clear"></div>
			</div>
		</div>
		';

		// Scripts
		foreach ($servant->theme()->scripts('domain') as $path) {
			$output .= '<script src="'.$path.'"></script>';
		}

		$output .= '
	</body>
</html>
';

echo $output;
?>