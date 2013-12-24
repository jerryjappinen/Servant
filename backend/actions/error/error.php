<?php

// Select page
$page = $servant->sitemap()->select($input->pointer());

// Error page via template
$template = $action->nestTemplate($servant->site()->template(), $page, '<h2>Something went wrong :(</h2>');

$action->status(500)->contentType('html')->output($template);

?>