<?php

// Use article title + content as action content
$servant->action()->output('<h1 class="title-article">'.$servant->article()->name().'</h1>'.$servant->article()->output());

// Output via template
$servant->action()->outputViaTemplate(true);

?>