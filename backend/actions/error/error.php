<?php

$action->status(500)->contentType('html')->output($action->nestTemplate($servant->site()->template(), '<h2>Something went wrong :(</h2>'));

?>