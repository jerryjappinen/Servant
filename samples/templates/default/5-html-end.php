<?php

// Include scripts
$tree = array();
if ($servant->action()->id() === 'read') {
	$tree = $servant->page()->tree();
}
echo '<script src="'.$servant->paths()->userAction('scripts', 'domain', $tree).'"></script>';

// End it all
echo '</div></body></html>';

?>