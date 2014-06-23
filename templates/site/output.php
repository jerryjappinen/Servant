<?php

$output = '
<div class="header">
	<h1><a href="'.$servant->paths()->root('domain').'">'.$servant->siteName().'</a></h1>
	<ul>
		'.$mainmenu.'
	</ul>
</div>

<div class="menus">
	'.implode_wrap('<ul>', '</ul>', $sectionmenus).'
</div>

<div class="body">
	';

	// Submenus
	if ($usePageAssets and $submenu) {
		$output .= '
		<div class="submenu">
			'.$submenu.'
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
	<h1><a href="'.$servant->paths()->root('domain').'">'.htmlspecialchars($servant->siteName()).'</a></h1>
</div>
';



// Output via HTML template
echo $template->nest('html', $output, $page, $usePageAssets);
?>