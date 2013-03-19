<?php

echo '
	<div id="footer">
		<h1>'.$servant->site()->name().'</h1>
		<ul>
			<li><a href="'.$servant->paths()->root('domain').$servant->site()->id().'/sitemap/'.'">Sitemap</a></li>
		</ul>
		'.
		// htmlDump($servant->site()->stylesheets()).
		'
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