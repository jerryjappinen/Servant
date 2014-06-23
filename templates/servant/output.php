<?php

/**
* Full printout
*/

$output = '
<div class="row row-menu">
	<div class="row-content buffer-left buffer-right">

		<h1><a href="'.$servant->paths()->root('domain').'">'.$page->siteName().'</a></h1>
		<ul class="reset plain collapse right">
			'.$mainmenu.'
		</ul>

		<div class="clear"></div>

	</div>
</div>

<div class="row row-body" id="body">
	<div class="row-content buffer clear-after">
	';

		// Submenus
		if ($usePageAssets and $submenu) {

			$output .= '
			<div class="submenu clear-after">
				<div class="hide-over-break">
					'.implode_wrap('<ul class="reset plain">', '</ul>', $sectionmenus).'
				</div>
				<div class="hide-under-break">
					'.$submenu.'
				</div>
			</div>
			';

		}

		// Page content
		$output .= '
		<div class="article clear-after">
			'.$template->content().'
			'.$prevnext.'
		</div>
		';

		$output .= '
	</div>
</div>
';

$output .= '
<div class="row row-footer">
	<div class="row-content buffer clear-after">
		'.$footer.'
	</div>
</div>
';

echo $template->nest('html', $output, $page, $usePageAssets);
?>