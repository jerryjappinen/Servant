<?php

// Set output
$action->contentType('html')->output($action->nestTemplate($servant->site()->template(), html_dump($output)));

?>