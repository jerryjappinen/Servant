<?php

$output = '
		<div class="dark buffer" id="footer">
			<div class="contentarea">

				<div class="column six">
					<table>

						<tr>
							<td class="third">Proot home</td>
							<td><a href="http://eiskis.net/proot/" target="_blank">eiskis.net/proot/</a></td>
						</tr>

						<tr>
							<td class="third">Documentation &amp; guides</td>
							<td><a href="http://eiskis.net/proot/guides/" target="_blank">eiskis.net/proot/guides/</a></td>
						</tr>
						<tr>
							<td class="third">Download</td>
							<td><a href="https://bitbucket.org/Eiskis/proot/downloads/proot.zip" target="_blank">Latest release from Bitbucket</a></td>
						</tr>
						<tr>
							<td class="third">Licensed under</td>
							<td><a href="../lgpl.txt" target="_blank">GNU Lesser General Public License</a></td>
						</tr>

					</table>
				</div>

				<div class="column six last">
					<table>

						<tr>
							<td class="third">Development @ Bitbucket</td>
							<td>
								<ul class="plain close">
									<li><a href="https://bitbucket.org/Eiskis/proot/" target="_blank">Project overview</a></li>
									<li><a href="https://bitbucket.org/Eiskis/proot/src" target="_blank">Source code</a></li>
									<li class="last"><a href="https://bitbucket.org/Eiskis/proot/issues?status=new&amp;status=open" target="_blank">Issue management</a></li>
								</ul>
							</td>
						</tr>
						<tr>
							<td class="third">By Jerry Jäppinen</td>
							<td>
								<ul class="plain close">
									<li><a href="https://twitter.com/Eiskis" target="_blank">@Eiskis</a></li>
									<li><a href="http://eiskis.net/" target="_blank">eiskis.net</a></li>
									<li><a href="mailto:eiskis@gmail.com" target="_blank">eiskis@gmail.com</a></li>
									<li class="last"><a href="tel:+358407188776">+358 40 7188776</a></li>
								</ul>
								<div class="clear"></div>
							</td>
						</tr>

					</table>
				</div>

				<div class="clear"></div>
			</div>
		</div>
		';

		// Scripts
		// foreach ($servant->theme()->scripts('domain') as $path) {
		// 	$output .= '<script src="'.$path.'"></script>';
		// }

		$output .= '
	</body>
</html>
';

echo $output;
?>