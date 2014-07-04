<?php

// Compose HTML to output
$output .= '
<div class="header">
	<h1><a href="'.$servant->paths()->root('domain').'">'.$servant->sitemap()->root()->siteName().'</a></h1>
	'.(!empty($mainmenu) ? '<ul>'.implode($mainmenu).'</ul>' : '').'
</div>

<div class="menus">
	'.implode_wrap('<ul>', '</ul>', $sectionMenus).'
</div>

<div class="body">
	';

	// Submenus
	if (!$page->isRoot() and $submenu) {
		$output .= '
		<div class="submenu">
			'.(!empty($submenu) ? $submenu : '').'
		</div>
		';
	}

	// Page content
	$output .= '
	<div class="page">
		'.$template->content().'
	</div>

</div>

<div class="footer">
	<h1><a href="'.$servant->paths()->root('domain').'">'.htmlspecialchars($servant->sitemap()->root()->siteName()).'</a></h1>
</div>
';

?>