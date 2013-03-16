<?php

// Use article title + content as action content
$this->content('<h1 class="title-article">'.$this->servant()->article()->name().'</h1>'.$this->servant()->article()->output());

// Output template
$this->output($this->servant()->template()->output());

?>