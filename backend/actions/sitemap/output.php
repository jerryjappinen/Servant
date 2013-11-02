<?php

// Create custom HTML for sitemap page
$action->output('<h1>Sitemap</h1>'.createNestedLists($servant, $servant->pages()->map()));

// Output via template
$action->outputViaTemplate(true);

?>