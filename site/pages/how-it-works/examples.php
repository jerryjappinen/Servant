
<?php

// List available sites
$j = 1;
for ($i=1; $i < 7; $i++) { 
	$j = $j > 3 ? 1 : $j;
	$id = 'site-'.$i;
	$name = 'Site '.$i;

	echo '
	<div class="column four '.($j === 3 ? 'last' : '').'" style="text-align: center;">
		<div class="buffer-right buffer-bottom">
			<p class="reset"><a href="http://eiskis.net/servant/'.$id.'/"><img src="/about/result.png" class="shadows" alt="'.$name.'" title="'.$name.'"></a></p>
			<h2 class="squeeze-top reset-bottom">'.$name.'</h2>
			<p class="reset"><a href="http://eiskis.net/servant/'.$id.'/">eiskis.net/servant/'.$id.'</a></p>
			<p class="reset-top">Foo</p>
		</div>
	</div>
	'.($j === 3 ? '<div class="clear"></div>' : '');
	$j++;
}
unset($i);

?>
