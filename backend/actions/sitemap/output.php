<?php

// Create custom HTML for sitemap page
$output = '<h1>Sitemap</h1>'.createNestedLists($servant, $servant->site()->articles());

// Output via template
$servant->action()->content($output);
$servant->action()->output($this->servant()->template()->output());

?>