<?php

$dump = $input->unserialize($input->serialize()) === $input->raw();

$action->status(200)->contentType('text')->output(dump($dump));

?>