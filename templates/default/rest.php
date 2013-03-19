<?php

echo '
	<div id="footer">
		<h4><strong>Developer stuff</strong></h4>
		'.htmlDump($servant->available()->themes()).'
	</div>
';

// Scripts
$scripts = $servant->theme()->scripts();
if (!empty($scripts)) {
	echo '<script src="'.$servant->paths()->root('domain').$servant->site()->id().'/scripts/'.'"></script>';
}
unset($scripts);

// End it all
echo '
	</body>
</html>
';

?>