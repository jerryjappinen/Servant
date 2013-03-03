
# Settings

This page guides you to the most important settings you may want to touch when installing Proot on your server. The defaults are fine for most cases, but you can change these at your will.

#### <strong>Always override defaults in `backend/hacks/` instead of changing *anything* in `backend/core/`. Override individual values only, not complete arrays.</strong>



## Miscellaneous

<table>
	
	<caption><code>$settings</code></caption>

	<tr>
		<th class="discreet fifth">Key</th>
		<th class="discreet fifth">Default value</th>
		<th class="discreet">Description</th>
	</tr>

	<tr>
		<td><code>'admin email'</code></td>
		<td><code>''</code></td>
		<td>A valid e-mail address to be included in author information and as sender of system-sent e-mails.</td>
	</tr>

	<tr>
		<td><code>'character set'</code></td>
		<td><code>'utf-8'</code></td>
		<td></td>
	</tr>

	<tr>
		<td><code>'http version'</code></td>
		<td><code>'HTTP/1.1'</code></td>
		<td></td>
	</tr>

	<tr>
		<td><code>'language'</code></td>
		<td><code>'en'</code></td>
		<td>Two-letter default language code sent out to clients.</td>
	</tr>

	<tr>
		<td><code>'timezone'</code></td>
		<td><code>'UTC'</code></td>
		<td></td>
	</tr>

	<tr>
		<td><code>'cache'</code></td>
		<td><code>0</code></td>
		<td>Maximum expiration time of cache files saved on the server, in minutes. Disabled when 0.</td>
	</tr>

	<tr>
		<td><code>'browser cache'</code></td>
		<td><code>12*60</code></td>
		<td>Default and maximum time for cache expiration recommended to the browser, in minutes. Disabled when 0.</td>
	</tr>

	<tr>
		<td><code>'cors'</code></td>
		<td><code>false</code></td>
		<td>Support for <a href="http://en.wikipedia.org/wiki/Cross-origin_resource_sharing">cross-origin requests</a>. Either boolean for turning on or off, or a CORS header string.<br /><strong>Note:</strong> on some servers you might have to also enable CORS in <code>.htaccess</code>.</td>
	</tr>

	<tr>
		<td><code>'debug'</code></td>
		<td><code>false</code></td>
		<td>Declare debug mode: leave output uncompressed, show full 500 error messages etc.</td>
	</tr>

</table>



## Database support

<table>
	
	<caption><code>$settings['database']</code></caption>

	<tr>
		<th class="discreet fifth">Key</th>
		<th class="discreet fifth">Default value</th>
		<th class="discreet">Description</th>
	</tr>

	<tr>
		<td><code>'username'</code><br /><code>'password'</code></td>
		<td><code>''</code></td>
		<td>Credentials for connecting to the database.</td>
	</tr>

	<tr>
		<td><code>'type'</code></td>
		<td><code>''</code></td>
		<td>The following are supported by the default record actions (via <a href="http://www.redbeanphp.com/manual/compatible" target="_blank">RedBeanPHP</a>): <code>'mysql'</code>, <code>'sqlite'</code>, <code>'pgsql'</code>, <code>'cubrid'</code>.<br /><strong>Currently only MySQL is being tested.</strong></td>
	</tr>

	<tr>
		<td><code>'address'</code></td>
		<td><code>'localhost'</code></td>
		<td>Location of your database. Leave this to <code>'localhost'</code> if you don't know any better.</td>
	</tr>

	<tr>
		<td><code>'database'</code></td>
		<td><code>'proot'</code></td>
		<td>The name of the database where the records for all apps are stored. If this is empty, each app will be assumed to have their own databases (which you have to create).</td>
	</tr>

	<tr>
		<td><code>'prefix'</code></td>
		<td><code>'proot'</code></td>
		<td>Prefixes table names in shared database or app-specific database names.</td>
	</tr>

</table>
