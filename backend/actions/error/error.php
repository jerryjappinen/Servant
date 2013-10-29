<?php
$servant->action()->outputViaTemplate(true)->status(400)->contentType('html')->output('<h2>Something went wrong :(</h2>');
?>