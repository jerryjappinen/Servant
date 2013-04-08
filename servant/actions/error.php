<?php
$servant->action()->outputViaTemplate(true);
$servant->action()->fail('<h1>Something went wrong :(</h1><p>We\'ve been notified now, and will fix this as soon as possible.</p>', 500);
?>