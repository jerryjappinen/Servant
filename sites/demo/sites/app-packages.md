
Apps are packages of frontend code that Proot can compile for a web browser to run. Apps are written in plain JavaScript, HTML, CSS and JS. **Basically, you create a new app by making a new directory to the `apps/` folder**.

Everything in an app package optional, to the point that even an empty directory is a valid app package and will run. Browse the included sample apps and templates to get the idea of how things can be done.



## Contents

Here is a short description of what app packages can contain:

<table>

	<tr>
		<td><code>shared/</code></td>
		<td>Code available in all your views. JS will be found under <code>app.shared</code> when running app.<br /><a href="?id=creating-views">Read more about shared code</a></td>
	</tr>
	<tr>
		<td><code>tools/</code></td>
		<td>Libraries and plugins packaged in your app package, not shared with other apps.<br /><a href="?id=creating-views">Read more about libraries &amp; plugins</a></td>
	</tr>
	<tr>
		<td><code>views/</code></td>
		<td>Structure, data, styles, scripts and assets for views.<br /><a href="?id=creating-views">Read more about views</a></td>
	</tr>
	<tr>
		<td><code>app.json</code></td>
		<td>Manifest file for e.g. meta information and plugin requirements.</td>
	</tr>
	<tr>
		<td><code>app.js</code></td>
		<td>Main launch method. This is run when the app starts.</td>
	</tr>
	<tr>
		<td><code>*.css</code>, <code>*.less</code></td>
		<td>Global styles.</td>
	</tr>
	<tr>
		<td><code>*.html</code></td>
		<td>Core template. If this exists, it will be delivered on initial page load.</td>
	</tr>

</table>

You can also include anything else you want. For example, it is common to have directories like `data/`, `images/` and `assets/` in app package root.



### Example package

#### `apps/dump/`

	images/
		upload/
			comics.jpg
			inversion.png
	shared/
		layout/
			grid.js
		splash/
			image.js
		bind.js
		overlay.js
	views/
		layout/
			grid/
				add.gif
				dom.json
				grid.html
				grid.less
				prepare.js
				render.js
				update.js
		splash/
			image/
				dom.json
				image.less
				left.gif
				prepare.js
				right.gif
				structure.html
				update.js
			upload/
				dom.json
				prepare.js
				spinner.gif
				structure.html
				upload.less
			kill.js
			prepare.js
			render.js
			splash.less
			unrender.js
		frame.less
		kill.js
		render.js
	app.jpg
	app.js
	app.json
