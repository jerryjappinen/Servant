
# Backend

With normal web sites that consist of static pages and forms, the response for every HTTP request is a complete HTML document - a complete page view, with meta information and menus. This doesn't really make sense, since the user is loading the same stuff multiple times per session.

The user should really have to load the base application only once, and load new views, content or functionality only when it's needed. Proot is not naturally skewed towards serving complete pages, but rather bite-sized, lightweight responses for clients. Proot's *backend actions* are great for AJAX applications that don't have to refresh page all the time.



## Operation

Proot's backend takes in HTTP requests, interprets them and then sends back a response. Proot will save you a lot of trouble when you want to handle stuff like input validation, content negotiation, HTTP status codes and output headers properly.

Each time a request comes in, it will be targeted to one and only one *app* and one *backend action*. This is important to keep in mind.

Proot works in a pretty simple manner: When a request comes in, `index.php` is run. Each phase of the overall process is run consecutively. If there are any errors, the processing will halt, and forward to error output. Simple enough.



## Core

After including `paths.php`, `index.php` will include the following files in order. There are [global variables](?category=cheatsheets&id=backend-variables) that roughly correspond to each.


<table>

	<tr>
		<td class="key"><code>constants.php</code></td>
		<td>Important values, names for and lists of things.</td>
	</tr>

	<tr>
		<td class="key"><code>settings.php</code></td>
		<td>System settings, commonly vary across environments.</td>
	</tr>

	<tr>
		<td class="key"><code>available.php</code></td>
		<td>Lists of available resources in the system.</td>
	</tr>

	<tr>
		<td class="key"><code>request.php</code></td>
		<td>Digging for and normalizing all the necessary info from the original request (accept headers, language, time, HTTP method etc.).</td>
	</tr>

	<tr>
		<td class="key"><code>choices.php</code></td>
		<td>Choosing app and action based on input parameters.</td>
	</tr>

	<tr>
		<td class="key"><code>input.php</code></td>
		<td>Validating, normalizing and prioritizing user input.</td>
	</tr>

	<tr>
		<td class="key"><code>app.php</code></td>
		<td>Extract the selected app from the app package, and find out what we need about it.</td>
	</tr>

	<tr>
		<td class="key"><code>action.php</code></td>
		<td>Run the selected backend action.</td>
	</tr>

	<tr>
		<td class="key"><code>response.php</code></td>
		<td>Prepare the response based on action and state.</td>
	</tr>

	<tr>
		<td class="key"><code>headers.php</code></td>
		<td>Compose HTTP header strings based on response contents.</td>
	</tr>

	<tr>
		<td class="key"><code>output.php</code></td>
		<td>Compose valid body content.</td>
	</tr>
</table>
