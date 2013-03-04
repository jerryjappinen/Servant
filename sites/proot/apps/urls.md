
When working with a framework, you often wonder where your final assets end up when you try to access them in your browser or static files. Fortunately, things are relatively simple with Proot: all natural paths work as expected, and there are a handful of other paths configured in `.htaccess`.

Here is a list of the default paths in Proot (try using these URLs in your browser):

<table>

	<tr>
		<th colspan="2">Natural directories</th>
	</tr>
	<tr>
		<td class="key"><code>apps/appname/</code></td>
		<td>Full, raw app packages, exactly as they appear in your file hierarchy.</td>
	</tr>
	<tr>
		<td class="key"><code>cache/appname/actionparent/actionname/</code></td>
		<td>Cache files, which may or may not exist.</td>
	</tr>
	<tr>
		<td class="key"><code>tools/</code></td>
		<td>Raw tools assets, i.e. all your plugins.</td>
	</tr>



	<tr>
		<th colspan="2">Redirects in .htaccess</th>
	</tr>
	<tr>
		<td class="key"><code>appname/</code></td>
		<td>Full web app.</td>
	</tr>
	<tr>
		<td class="key"><code>use/appname/actionparent/actionname/</code></td>
		<td>Accessing a specific backend action for an app. Parameters can be added to the end, separated with slashes.</td>
	</tr>



</table>

Use [p.paths](?category=cheatsheets&id=js-library) to generate paths dynamically in JavaScript.



## Naming convention

There are minor restrictions and conventions for naming directories and files in app packages and elsewhere.

### Reserved words

- Any directory in Proot's root should **not** share a directory name with any app package.
- No app or directory in Proot's root directory or app package should be named `use` (the keyword for accessing backend action, defined in `.htaccess`).



## Path replacements in stylesheets and HTML templates

When HTML or CSS is compiled to the browser, all URLs are converted into absolute or root-relative format so they work as expected anywhere in the domain. Here are examples:

### CSS examples

#### `apps/appname/views/popups/slideshow/slideshow.css`

	// Relative URLs point to the location of the CSS file itself.
	url(next.jpg)									// -> url(/path/to/proot/apps/appname/views/popups/slideshow/next.jpg)
	url(icons/next.jpg)								// -> url(/path/to/proot/apps/appname/views/popups/slideshow/icons/next.jpg)



	// URLs that begin with a slash are targeted to the root of the app package.
	url(/app-icon.png)								// -> url(/path/to/proot/apps/appname/app-icon.png)
	url(/assets/icons/app.png)						// -> url(/path/to/proot/apps/appname/assets/icons/app.png)



	// However, the "use" endpoint is treated as a special case to target backend actions.
	//   - App name will be added automatically, you should skip it.
	//   - A directory named "use" in the app package root cannot be directly targeted via this kind of a URL in CSS (sorry).
	//   - On the bright side, you can use Proot's backend to scale and crop background images.

	url(/use/images/show/images%252Fbackgrounds%252Fapp-logo.png)
		// -> url(/path/to/proot/use/appname/images/show/images%252Fbackgrounds%252app-logo.png)

	url(/use/images/show/images%252Fbackgrounds%252Fapp-logo.png/200/600/-1)
		// -> url(/path/to/proot/use/appname/images/show/images%252Fbackgrounds%252app-logo.png/200/600/-1)

### HTML examples

#### `apps/appname/views/popups/slideshow/template.html`

	// App package root
	<a href="/images/logos/big.png"><img src="/images/logos/small.png"></a>
		// "/path/to/proot/apps/appname/images/logos/big.png" (same path for "small.png")

	// Relative URL points to the location of the template file
	<a class="close"><img src="close-button.png"></a>
		// "/path/to/proot/apps/appname/views/popups/slideshow/close-button.png"

	// The "use" special case works like in stylesheets
	<h1><img src="/use/images/show/images%252Flogos%252Fbig.png/150"></h1>
		// -> "/path/to/proot/use/appname/images/show/images%252Flogos%252big.png/150"
