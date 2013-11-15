<?php

// Create custom HTML for sitemap page
$output = '<h1>Sitemap</h1>'.html_dump($sitemap->dump());
$output = $action->nestTemplate($servant->site()->template(), $output);

// Output via template
$action->contentType('html')->output($output);

?>