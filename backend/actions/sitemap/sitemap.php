<?php

// Select page
$page = $servant->sitemap()->select($input->pointer())->page();

// Create custom HTML for sitemap page
$message = '<h1>Sitemap</h1>'.html_dump($servant->sitemap()->dump());



// FLAG I can't know what content the template wants - I'm assuming the same as site action
$template = $servant->create()->template($servant->site()->template(), $message, $page);

// Output via template
$action->contentType('html')->output($template->output());

?>