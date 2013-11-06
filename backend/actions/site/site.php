<?php

$pageAction = $action->nest('page');
log_dump($pageAction->id());

// Output page content
$action->outputViaTemplate(true)->output($pageAction->output());

?>