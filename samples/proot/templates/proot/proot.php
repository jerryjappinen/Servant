<?php

// Header
$output = '
<div class="dark" id="menu">

	<div class="buffer first">
		<div class="contentarea">
			<ol class="plain push-left block reset">
			';

			// Level 1 menu
			$level1 = $servant->pages()->map();
			if (!empty($level1)) {
				foreach ($level1 as $key => $tempPage) {
					$output .= '<li class="reset'.($page->pointer(0) === $key ? ' selected': '').'"><a href="'.$tempPage->readPath('domain').'">'.$tempPage->name().'</a></li>';
				}
			}
			unset($level1, $key, $tempPage);

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
			$level2 = $servant->pages()->map($page->pointer(0));
			if (!empty($level2) and is_array($level2)) {
				foreach ($level2 as $key => $tempPage) {
					$output .= '<li class="reset'.($page->pointer(1) === $key ? ' selected': '').'"><a href="'.$tempPage->readPath('domain').'/">'.$tempPage->name().'</a></li>';
				}
			}
			unset($level2, $key, $tempPage);

			$output .= '
			</ol>
			';

			// Two-level dropdown menu
			$output .= '<select class="menu-1 menu-2 menu-1-2" onchange="window.open(this.options[this.selectedIndex].value,\'_top\')">';
			foreach ($servant->pages()->map() as $key => $tempPage) {

				// Nested
				if (is_array($tempPage)) {

					// Wrap in optgroup
					$output .= '<optgroup label="'.$key.'">';
					foreach ($tempPage as $key2 => $tempPage2) {
						$output .= '<option value="'.$tempPage2->readPath('domain').'">'.$tempPage2->name().'</option>';
					}
					$output .= '</optgroup>';

				// First-level page
				} else if (!$tempPage->children()) {
					$output .= '<option value="'.$tempPage->readPath('domain').'">'.$tempPage->name().'</option>';

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
';



// Content
$output .= '
<div class="buffer" id="content">
	<div class="contentarea">
		';

		// One-column layout
		$current = count($page->pointer())-1;
		if ($current < 2) {
			$output .= $template->content();

		// Two-column layout
		} else {
			$output .= '
			<div class="column first nine">
				'.$template->content().'
			</div>
			<div class="column last three" id="submenu">

				<div class"buffer">
					<ol class="reset">
					';

					// Level 3 menu
					$level3 = $servant->pages()->templates($page->pointer(0), $page->pointer(1));
					if (!empty($level3) and is_array($level3)) {
						foreach ($level3 as $key => $value) {
							$output .= '<li class="reset'.($page->pointer(2) === $key ? ' selected': '').'"><a href="'.$servant->paths()->root('domain').$servant->site()->id().'/'.$action->id().'/'.$page->pointer(0).'/'.$page->pointer(1).'/'.$key.'/">'.$key.'</a></li>';
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

// Output via the default template
echo $template->nest('html', $output);
?>