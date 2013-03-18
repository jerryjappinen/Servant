<?php

echo '
	<div id="footer">
		<h4><strong>Developer stuff</strong></h4>
		'.htmlDump($servant->site()->articles()).'
	</div>
';

// Scripts
echo '<script src="'.$servant->paths()->root('domain').$servant->site()->id().'/scripts/'.'"></script>';

// End it all
echo '
	</body>
</html>
';

?>