<?php

$action->contentType('txt')->output(dump($action->data()->rglob()));

?>