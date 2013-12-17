<?php

/**
* Full template
*/

$frame = '
<div class="row row-menu">
	<div class="row-content buffer-left buffer-right">

		<h1><a href="'.$servant->paths()->root('domain').'">'.$servant->site()->name().'</a></h1>
		<ul class="reset plain collapse">
			'.$mainmenu.'
		</ul>

		<div class="clear"></div>

	</div>
</div>

<div class="row row-body">
	<div class="row-content buffer clear-after">
	';

		if ($action->isRead() and $submenu) {

			$frame .= '
			<div class="submenu clear-after">'.$submenu.'</div>
			';

		}

		$frame .= '
		<div class="article clear-after">
			'.$template->content().'
		</div>
		';

		$frame .= '
	</div>
</div>
';

$frame .= '
<div class="row row-footer">
	<div class="row-content buffer clear-after">
		'.$footer.'
	</div>
</div>
';

echo $template->nest('html', $frame);
?>