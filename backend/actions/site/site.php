<?php

// Select page
$page = $servant->sitemap()->select($input->pointer());

// Nest the page action
$pageAction = $action->nest('page');
$output = $action->nestTemplate($page->template(), $page, $pageAction->output());

// Output page content
$action->contentType('html')->contentType($pageAction->contentType())->output($output);
?>