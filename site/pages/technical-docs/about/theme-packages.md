
# Themes

The styles and scripts that create the look and feel for any template are called a theme. Usually a theme is made for a specific kind of template.

Themes consist of stylesheets, scripts and accompanying assets. All the files under `site/theme/` are considered to be a part of a theme. **All theme files are loaded automatically, alphabetically and recursively**.



### Styles & scripts

Servant supports `.css`, `.less` and `.scss` files out of the box. They will be minified and provided as a single, compiled stylesheet file automatically. For this reason, there's **no reason to use `@import` statements in stylesheets**.

For scripts, `.js` files are used. All JavaScript files are compiled into a one file.

**Note!** Remember, that pages can include their own stylesheets and scripts. These can override the generic styles or scripts in a theme.



### Example

Let's take the default theme as an example:

##### themes/default/
	images/
		menu.png
	scripts/
		externalLinks.js
		respond.min.js
		responsive-nav.min.js
	styles/
		defaults/
			base.less
			code.less
			images.less
			lists.less
			tables.less
			transitions.less
		layout/
			frame.less
			header.less
			responsive-nav.less
			sidebar.less
		look/
			body.less
			footer.less
			header.less
			sidebar.less
		_vars.less
		layers.css
		prefixer.less
		responsive.less
	favicon.ico

The first `.css` and `.less` files to be included are on the root level of `styles/` (`_vars.less` etc.), followed by what's in `styles/defaults/`, then `styles/layout/` and so on. The same logic is applied for all script files.
