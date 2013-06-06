<?php

// Include scripts
// FLAG I really shouldn't hardcode the name of read action...
if ($servant->action()->id() === 'read') {
	$temp = implode('/', $servant->site()->article()->tree()).'/';
} else {
	$temp = '';
}
echo '<script src="'.$servant->paths()->root('domain').$servant->site()->id().'/scripts/'.$temp.'"></script>';

// End it all
echo '</div></body></html>';

?>