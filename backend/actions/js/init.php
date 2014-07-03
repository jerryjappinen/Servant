<?php

// Select root by default, page node if pointer given
$page = $servant->sitemap()->root();
if (count($input->pointer())) {
	$page = $servant->sitemap()->select($input->pointer())->page();
}

$constants = array();

?>