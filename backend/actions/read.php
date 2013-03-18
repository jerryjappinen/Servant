<?php

// Use article title + content as action content
$servant->action()->content('<h1 class="title-article">'.$servant->article()->name().'</h1>'.$servant->article()->output());

// Output template
$servant->action()->output($this->servant()->template()->output());

?>