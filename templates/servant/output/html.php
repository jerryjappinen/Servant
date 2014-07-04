<?php

// Compose HTML to output
$output .= '
<div class="row row-menu">
	<div class="row-content buffer-left buffer-right">

		<h1><a href="'.$servant->paths()->root('domain').'">'.$page->siteName().'</a></h1>
		<ul class="reset plain collapse right">
			'.implode($mainmenu).'
		</ul>

		<div class="clear"></div>

	</div>
</div>

<div class="row row-body" id="body">
	<div class="row-content buffer clear-after">
	';

		// Submenus
		if (!$page->isRoot() and $submenu) {

			$output .= '
			<div class="submenu clear-after">
				<div class="hide-over-break">
					'.implode_wrap('<ul class="reset plain">', '</ul>', $sectionMenus).'
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
		<h1><a href="'.$servant->paths()->root('domain').'" data-target="body" class="plain scroll">This is Servant<span class="inline-block pull-right">.</span></a></h1>
	</div>
</div>
';

?>