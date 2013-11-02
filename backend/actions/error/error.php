<?php

$action->output('<h2>Something went wrong :(</h2>');

$action->outputViaTemplate(true)->status(500)->contentType('html');

?>