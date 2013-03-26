<?php

// Include scripts
echo '<script src="'.$servant->paths()->root('domain').$servant->site()->id().'/scripts/'.implode('/', $servant->site()->article()->tree()).'/'.'"></script>';

// End it all
echo '</body></html>';

?>