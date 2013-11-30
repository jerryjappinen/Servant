<?php

/**
* Full template
*/

$frame = '
<div class="row row-header">
	<div class="row-content buffer clear-after">
		<h1><a href="'.$servant->paths()->root('domain').'">'.$servant->site()->name().'</a></h1>
		'.$mainmenu.'
	</div>
</div>

<div class="row row-body">
	<div class="row-content buffer clear-after">
	';

		if ($submenu) {

			$frame .= '
			<div class="column three submenu">'.$submenu.'</div>

			<div class="column nine last article">
				'.$template->content().'
			</div>
			';

		} else {

			$frame .= '
			<div class="article">
				'.$template->content().'
			</div>
			';

		}

		$frame .= '
	</div>
</div>

<!--
<div class="row row-footer">
	<div class="row-content buffer clear-after">
		'.$footer.'
	</div>
</div>
-->

';

echo $template->nest('html', $frame);
?>