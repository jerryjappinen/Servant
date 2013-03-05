<?php

// Scripts
foreach ($servant->theme()->scripts('domain') as $path) {
	echo '<script src="'.$path.'"></script>';
}

echo '
	</body>
</html>
';

?>