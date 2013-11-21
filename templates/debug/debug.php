<?php
if ($servant->debug()) {
	$content = $servant->warnings()->all();
	echo $content ? html_dump($content) : '';
}
?>