<?php

// Include scripts
$tree = array();
if ($servant->action()->id() === 'read') {
	$tree = $servant->pages()->current()->tree();
}
echo '<script src="'.$servant->paths()->userAction('scripts', 'domain', $tree).'"></script>';

// End it all
echo '</div></body></html>';

?>