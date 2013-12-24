<?php

$action->contentType('txt')->output($action->contentType().': '.implode('/', $input->pointer()));

?>