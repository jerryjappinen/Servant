
<?php

// List available sites
$j = 1;
for ($i=1; $i < 7; $i++) { 
	$j = $j > 3 ? 1 : $j;
	$id = 'site-'.$i;
	$name = 'Site '.$i;

	echo '
	<div class="column four center '.($j === 3 ? 'last' : '').'">
		<div class="buffer-right buffer-bottom">
			<p class="close"><a href="http://eiskis.net/servant/'.$id.'/"><img src="/about/result.png" class="shadows" alt="'.$name.'" title="'.$name.'"></a></p>
			<h2 class="squeeze-top close-bottom">'.$name.'</h2>
			<p class="close"><a href="http://eiskis.net/servant/'.$id.'/">eiskis.net/servant/'.$id.'</a></p>
			<p class="close-top">Default template, default theme + custom themes</p>
		</div>
	</div>
	'.($j === 3 ? '<div class="clear"></div>' : '');
	$j++;
}
unset($i);

?>
