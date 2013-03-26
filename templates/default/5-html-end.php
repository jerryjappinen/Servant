<?php

// Include scripts
$scripts = $servant->theme()->scripts();
if (!empty($scripts)) {
	echo '<script src="'.$servant->paths()->root('domain').$servant->site()->id().'/scripts/'.'"></script>';
}
unset($scripts);

// End it all
echo '</body></html>';

?>