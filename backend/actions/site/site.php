<?php

// Select page
$page = $servant->sitemap()->select($input->pointer())->page();

// Get page content via page action
$pageAction = $action->nest('page');

// FLAG I can't know what content the template wants - I'm just assuming this
$template = $servant->create()->template($page->template(), $pageAction->output(), $page, true);

// Output page content
$action->contentType($pageAction->contentType())->output($template->output());
?>