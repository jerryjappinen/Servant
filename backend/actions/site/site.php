<?php

// Nest the page action
$pageAction = $action->nest('page');

// Output page content
$action->outputViaTemplate(true)->contentType($pageAction->contentType())->output($pageAction->output());

?>