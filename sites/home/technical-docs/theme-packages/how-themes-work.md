
# Themes

The styles and scripts that create the look and feel for any template are packaged into themes. One theme will always be selected when a web site is generated for a user. Usually a theme is made for a specific template, and one template can look very different when using different themes.



## Selecting a theme for a site

For each site, one of the available specific themes is selected. Since Servant always attempts to get something meaningful out to the user, it also tries to figure out the best theme to use for each site.

Here's the order of preference used when selecting a theme.

1. Theme defined in site's configuration.
2. The theme with the same name as the current *site*.
3. The theme with the same name as the current *template*.
4. The default theme defined in global settings.
5. The first theme available.



## Theme packages

Themes consist of stylesheets, scripts and accompanying assets. They are contained in one directory (although a single style or script file is also allowed in the theme directory). **All theme files are loaded automatically, alphabetically and recursively**.

### Styles & scripts

Servant supports both `.css` and `.less` files. They will be minified and provided as a single merged stylesheet for the users automatically. **You should not use `@import`** in the style files, since all files will be automatically loaded in the predetermined order anyway.

For scripts, `.js` files are used. All script files are merged into a single, file for users.

**Note!** Remember, that sites can also include custom stylesheets and scripts, which will override anything in themes.



### Example

Let's take the default theme as an example:

##### themes/default/
	scripts/
		menus.js
		respond.min.js
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
			sidebar.less
		look/
			body.less
			footer.less
			header.less
			sidebar.less
		_vars.less
		layers-base.css
		layers-responsive.less
		prefixer.less
	readme.txt
	servant.ico

The first files to be included are the `.css` and `.less` files on the root level of `styles/` (`_vars.less` is first), followed by what's in `styles/defaults/`, then `styles/layout/` and so on. The same logic is applied for `.js` files.
