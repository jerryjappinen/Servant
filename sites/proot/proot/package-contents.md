
# What is included

Here's a quick tour of what's included in the Proot download so you get an idea of how things work.



## root

<table>

	<tr>
		<td><code>apps/</code></td>
		<td>Your app packages</td>
	</tr>
	<tr>
		<td><code>cache/</code></td>
		<td>Storage for cache files</td>
	</tr>
	<tr>
		<td><code>dev/</code></td>
		<td>Developer tools for troubleshooting and system info</td>
	</tr>
	<tr>
		<td><code>guides/</code></td>
		<td>Documentation and help</td>
	</tr>
	<tr>
		<td><code>tools/</code></td>
		<td>Frontend libraries and utilities you can use in your apps</td>
	</tr>
	<tr>
		<td><code>templates/</code></td>
		<td>App package templates (interchangeable with apps)</td>
	</tr>
	<tr>
		<td><code>.htaccess</code></td>
		<td>Configurations for Apache, mainly for friendly URLs</td>
	</tr>
	<tr>
		<td><code>lgpl.txt</code></td>
		<td>Licensing information</td>
	</tr>
	<tr>
		<td><code>readme.txt</code></td>
		<td>Quick start guide</td>
	</tr>

</table>



## backend/

<table>

	<tr>
		<td><code>actions/</code></td>
		<td>Backend actions that Proot runs when a request comes in</td>
	</tr>
	<tr>
		<td><code>helpers/</code></td>
		<td>Some simple helper functions required by the server code</td>
	</tr>
	<tr>
		<td><code>errors/</code></td>
		<td>Error page templates for various formats</td>
	</tr>
	<tr>
		<td><code>hacks/</code></td>
		<td>Custom overrides to server code</td>
	</tr>
	<tr>
		<td><code>core/</code></td>
		<td>The actual Proot server code (these are executed when index.php starts)</td>
	</tr>
	<tr>
		<td><code>utilities/</code></td>
		<td>Generic utilities available for actions</td>
	</tr>
	<tr>
		<td><code>index.php</code></td>
		<td>The main execution file (run when a request comes in)</td>
	</tr>
	<tr>
		<td><code>path.php</code></td>
		<td>Defines internal path names</td>
	</tr>

</table>
