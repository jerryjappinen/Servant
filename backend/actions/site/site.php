<?php

// Output page content
$action->output($servant->pages()->current()->output());

// Output via template
$action->outputViaTemplate(true);

?>