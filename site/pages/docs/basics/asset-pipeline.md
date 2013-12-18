
# Asset pipeline

The styles, scripts and accompanying assets that create the look and feel for any template are located under the assets directory (`site/assets/` by default). They usually target a specific template at least to some extent.

Servant supports `.css`, `.less` and `.scss` files out of the box. For scripts, only `.js` files are used.



### Autoloading

All stylesheets and scripts are loaded automatically; *alphabetically* and *recursively*. Servant merges, minifies and caches asset files automatically.

Pages can include their own assets. For each page, the assets in its directory and parent directories are loaded. Use these to override global styles and behavior.

There's no reason to use `@import` statements in stylesheets, as all source files are autoloaded.



### Example

##### site/assets/
	images/
		menu.png
	scripts/
		externalLinks.js
		responsive-nav.min.js
	styles/
		defaults/
			base.less
			code.less
			lists.less
			tables.less
			transitions.less
		layout/
			body.less
			header.less
			footer.less
		layers.css
		prefixer.less
		responsive.css

The first `.css` and `.less` files to be loaded are on the root level of `styles/` (`layers.css` etc.), followed by `styles/defaults/`, then `styles/layout/` and so on. The same logic applies for scripts.

Currently, *SASS* and *LESS* can't be mixed under `assets/`.
