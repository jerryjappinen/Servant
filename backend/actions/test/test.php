<?php

$dump = $action->data()->rglob();
// $dump = $action->data()->open($dump[0]);

$action->contentType('txt')->output(dump($dump));

?>