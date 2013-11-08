<?php

// Create custom HTML for sitemap page
$output = '<h1>Sitemap</h1>'.createNestedLists($servant, $servant->pages()->map());
$output = $action->nestTemplate($servant->site()->template(), $output);

// Output via template
$action->contentType('html')->output($output);

?>