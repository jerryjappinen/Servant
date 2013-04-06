
<?php

// List available sites
$i = 1;
foreach ($servant->available()->sites() as $id) {

	echo '
	<div class="column four center '.($i === 3 ? 'last' : '').'">
		<div class="buffer-right buffer-bottom">
			<p class="close"><a href="http://eiskis.net/servant/bootstrap/"><img src="/about/result.png" class="shadows" alt="Web site" title="...into this!"></a></p>
			<h2 class="squeeze-top close-bottom">'.$servant->format()->name(basename($id)).'</h2>
			<p class="close"><a href="http://eiskis.net/servant/bootstrap/">eiskis.net/servant/bootstrap</a></p>
			<p class="close-top">Default template, default theme + custom themes</p>
		</div>
	</div>
	'.($i === 3 ? '<div class="clear"></div>' : '');

	// Counter
	$i = $i === 3 ? 1 : $i+1;
}
unset($i);

?>
