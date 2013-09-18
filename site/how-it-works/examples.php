
<?php

// List available sites
$i = 1;
foreach ($servant->available()->sites() as $id) {
	$name = $servant->format()->name($id);

	echo '
	<div class="column four center '.($i === 3 ? 'last' : '').'">
		<div class="buffer-right buffer-bottom">
			<p class="close"><a href="http://eiskis.net/servant/'.$id.'/"><img src="/about/result.png" class="shadows" alt="'.$name.'" title="'.$name.'"></a></p>
			<h2 class="squeeze-top close-bottom">'.$name.'</h2>
			<p class="close"><a href="http://eiskis.net/servant/'.$id.'/">eiskis.net/servant/'.$id.'</a></p>
			<p class="close-top">Default template, default theme + custom themes</p>
		</div>
	</div>
	'.($i === 3 ? '<div class="clear"></div>' : '');

	// Counter
	$i = $i === 3 ? 1 : $i+1;
}
unset($i);

?>
