<?php

// HTML headers
$output = '
<!DOCTYPE html>
<html lang="'.$servant->site()->language().'">
	<head>
		';

		// Basic meta stuff - charset, scaling...
		$output .= '
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<style type="text/css">
			@-ms-viewport{width: device-width;}
			@-o-viewport{width: device-width;}
			@viewport{width: device-width;}
		</style>
		';

		// Site title
		$output .= '
		<title>'.$servant->site()->name().'</title>
		<meta name="application-name" content="'.$servant->site()->name().'">
		';

		// Custom web site icon
		$icon = $servant->site()->icon('domain');
		if (empty($icon)) {
			$icon = $servant->theme()->icon('domain');
		}
		if (!empty($icon)) {
			$extension = pathinfo($icon, PATHINFO_EXTENSION);

			// .ico for browsers
			if ($extension === 'ico') {
				$output .= '<link rel="shortcut icon" href="'.$icon.'" type="image/x-icon">';

			// Images for browsers, iOS, Windows 8
			} else {
				$output .= '
				<link rel="icon" href="'.$icon.'" type="'.$servant->settings()->contentTypes($extension).'">
				<link rel="apple-touch-icon-precomposed" href="'.$icon.'" />
				<meta name="msapplication-TileImage" content="'.$icon.'"/>';
				// $output .= '<meta name="msapplication-TileColor" content="#d83434"/>';
			}

			unset($extension);
		}
		unset($icon);



		// Stylesheets
		$output .= '<link rel="stylesheet" href="'.$servant->paths()->root('domain').$servant->site()->id().'/stylesheets/'.implode('/', $servant->pages()->current()->tree()).'/'.'" media="screen">';

		$output .= '
	</head>
';



// Create classes for body class
$i = 1;
$classes = array();
$tree = $servant->page()->tree();
foreach ($tree as $value) {
	$classes[] = 'page-'.implode('-', array_slice($tree, 0, $i));
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
					$level1 = $servant->pages()->files();
					if (!empty($level1)) {
						foreach ($level1 as $key => $value) {
							$output .= '<li class="reset'.($servant->page()->tree(0) === $key ? ' selected': '').'"><a href="'.$servant->paths()->root('domain').$servant->site()->id().'/'.$servant->action()->id().'/'.$key.'/">'.$servant->format()->title($key).'</a></li>';
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
					$level2 = $servant->pages()->files($servant->page()->tree(0));
					if (!empty($level2) and is_array($level2)) {
						foreach ($level2 as $key => $value) {
							$output .= '<li class="reset'.($servant->page()->tree(1) === $key ? ' selected': '').'"><a href="'.$servant->paths()->root('domain').$servant->site()->id().'/'.$servant->action()->id().'/'.$servant->page()->tree(0).'/'.$key.'/">'.$servant->format()->title($key).'</a></li>';
						}
					}
					unset($level2, $key, $value);

					$output .= '
					</ol>
					';

					// Two-level dropdown menu
					$output .= '<select class="menu-1 menu-2 menu-1-2" onchange="window.open(this.options[this.selectedIndex].value,\'_top\')">';
					foreach ($servant->pages()->files() as $key => $value) {

						// First-level page
						if (is_string($value) or is_array($value) and count($value) === 1) {
							$output .= '<option value="'.$servant->paths()->root('domain').$servant->site()->id().'/'.$servant->action()->id().'/'.$key.'/">'.$servant->format()->title($key).'</option>';

						// Nested
						} else if (is_array($value)) {

							// Wrap in optgroup
							$output .= '<optgroup label="'.$servant->format()->title($key).'">';
							foreach ($value as $key2 => $value2) {
								$output .= '<option value="'.$servant->paths()->root('domain').$servant->site()->id().'/'.$servant->action()->id().'/'.$key.'/'.$key2.'/">'.$servant->format()->title($key2).'</option>';
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
				$current = count($servant->page()->tree())-1;
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
							$level3 = $servant->pages()->files($servant->page()->tree(0), $servant->page()->tree(1));
							if (!empty($level3) and is_array($level3)) {
								foreach ($level3 as $key => $value) {
									$output .= '<li class="reset'.($servant->page()->tree(2) === $key ? ' selected': '').'"><a href="'.$servant->paths()->root('domain').$servant->site()->id().'/'.$servant->action()->id().'/'.$servant->page()->tree(0).'/'.$servant->page()->tree(1).'/'.$key.'/">'.$servant->format()->title($key).'</a></li>';
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

		// Include scripts
		echo '<script src="'.$servant->paths()->root('domain').$servant->site()->id().'/scripts/'.implode('/', $servant->pages()->current()->tree()).'/'.'"></script>';

		$output .= '
	</body>
</html>
';

echo $output;
?>