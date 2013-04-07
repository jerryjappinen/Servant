<?php

// Use article title + content as action content
$servant->action()->output($servant->article()->output());

// Output via template
$servant->action()->outputViaTemplate(true);

?>