<?php

// Components
$mainmenu = $template->nest('list-toplevelpages');
$submenu = $template->nest('list-submenu');
$article = $template->nest('page');

// Header
$output = '
<div class="row row-header">
	<div class="row-content">
		<h1><a href="'.$servant->paths()->root('domain').'">'.$servant->site()->name().'</a></h1>
		<ul>
			'.$mainmenu.'
		</ul>
	</div>
</div>
';

// Body content
$output .= '
<div class="row row-body">
	<div class="row-content">
	';

		// Submenus
		if ($template->isSite() and $submenu) {

			$output .= '
			<div class="submenu">
				'.$submenu.'
			</div>
			';

		}

		// Page content
		$output .= '
		<div class="article">
			'.$template->content().'
		</div>
		';

		$output .= '
	</div>
</div>
';

// Footer
$output .= '
<div class="row row-footer">
	<div class="row-content">
		<h1><a href="'.$servant->paths()->root('domain').'">'.$servant->site()->name().'</a></h1>
	</div>
</div>
';

echo $template->nest('html', $output);
?>