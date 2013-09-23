<?php

// Create custom HTML for sitemap page
$servant->action()->output('<h1>Sitemap</h1>'.createNestedLists($servant, $servant->pages()->files()));

// Output via template
$servant->action()->outputViaTemplate(true);

?>