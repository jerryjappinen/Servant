<?php

// Nest the page action
$pageAction = $action->nest('page');
$output = $action->nestTemplate($servant->site()->template(), $pageAction->output());

// Output page content
$action->contentType('html')->contentType($pageAction->contentType())->output($output);
?>