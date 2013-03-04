
*Actions* are PHP scripts that expose some data or functionality from the backend to the outside world. They answer to HTTP requests, which means loading their URL in a browser or sending an AJAX request. They are **shared by all apps**, and readily usable via a lightweight API, so it's very effortless to reuse existing functionality in multiple apps without having to touch backend code.

A good example of a backend action is outputting a file from an app package, where input parameters select the file &ndash; this type of behavior is very commonly required, and in Proot is provided in all apps.

It's easy to build more backend actions, and there's very little behind-the-scenes magic to how they work. Proot takes care of status codes, content type headers, caching, formatting details, input validation etc. for all actions.

Actions are grouped into parent directories, which collect related behavior into namespaces that are easy to remember.



## File structure & operation

Actions consist of four distinct parts. In your scripts you are...

1. declaring some settings (e.g. for input validation),
2. making sure everything will work with custom validations (e.g. that the requested file exists),
3. doing whatever it is your action should do (e.g. rename a file), and
4. generating output to the user (e.g. the contents of a markdown file in HTML).

So, when you create an action, you actually create 1&ndash;4 `.php` files in a directory (`backend/actions/:parent/:id/`). At least one of these files must exist.

1. `configuration.php`
2. `validation.php`
3. `action.php`
4. `output.php`

After configuration, Proot will make sure all the dependencies and utilities are there for you, and that user input is in order. After this, you can rely that `$input` has passed the basic validation. Any custom validations you have written will be included after that, and if everything is still OK, the actual code in your action is run.

Internally, `action.php` and `output.php` are handled identically, they're just available for you to organize your code if you're doing something bigger.



## $action = array();

In practice, you use the `$action` array to control what and how you respond to a request.

- Include basic settings in `configuration.php`.
- Set *content type*, *status* and *output* in any file, as long as they are there when the action is done.
- You must create some output. An empty string is valid, but you must define it explicitly.
- Default status code (when there are no errors) is **200**. Other defaults are context-sensitive.

<table>

	<tr>
		<th colspan="3">Available (don't override)</th>
	</tr>
	<tr>
		<td class="key"><code>$action['id']</code></td>
		<td colspan="2">Name of your action (i.e. the name of the directory the action's files are in).</td>
	</tr>
	<tr>
		<td class="key"><code>$action['parent']</code></td>
		<td colspan="2">Action's parent (directory) name.</td>
	</tr>



	<tr>
		<th colspan="3">Optional in <code>configuration.php</code></th>
	</tr>
	<tr>
		<td class="key"><code>$action['cache']</code><br /><code>$action['browser cache']</code><br /><code>$action['cache file output']</code></td>
		<td>Server and browser cache settings.</td>
		<td><a href="?category=actions&amp;id=caching">Read more</a></td>
		</td>
	</tr>
	<tr>
		<td class="key"><code>$action['dependencies']</code></td>
		<td>List of other actions that are expected to be available in the system.</td>
		<td><a href="?category=actions&amp;id=dependencies">Read more</a></td>
		</td>
	</tr>
	<tr>
		<td class="key"><code>$action['input']</code></td>
		<td>Declaration of accepted user input. This is used for automatic validation.</td>
		<td><a href="?category=actions&amp;id=input">Read more</a></td>
		</td>
	</tr>
	<tr>
		<td class="key"><code>$action['utilities']</code></td>
		<td>List of utilities (i.e. shared code) to be included. These will be available in <code>action.php</code> and <code>output.php</code>.</td>
		<td><a href="?category=actions&amp;id=utilities">Read more</a></td>
		</td>
	</tr>



	<tr>
		<th colspan="3">Expected after <code>output.php</code></th>
	</tr>
	<tr>
		<td class="key"><code>$action['content type']</code></td>
		<td colspan="2">The content type of the response.</td>
	</tr>
	<tr>
		<td class="key"><code>$action['file output']</code></td>
		<td>If you want to output the content of the file, you can set this to <code>'redirect'</code> or <code>'read file'</code>. Otherwise, let it be <code>false</code>.</td>
		<td></td>
		</td>
	</tr>
	<tr>
		<td class="key"><code>$action['output']</code></td>
		<td>The output data that Proot will serve to the user.</td>
		<td><a href="?category=actions&amp;id=output">Read more</a></td>
		</td>
	</tr>
	<tr>
		<td class="key"><code>$action['status']</code></td>
		<td colspan="2">The status code of the response as an integer.</td>
	</tr>

</table>
