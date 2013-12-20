
# Servant

Servant is a practical and approachable micro framework for web developers, designed for us humans who prefer understandable environments and frequently whip up new web sites.

- Intro, download & docs
	- [servantframework.com](http://servantframework.com/)
- Source & issues
	- [github.com/Eiskis/Servant](https://github.com/Eiskis/Servant)
- By Jerry Jäppinen
	- Released under LGPL
	- [eiskis@gmail.com](mailto:eiskis@gmail.com)
	- [eiskis.net](http://eiskis.net/)
	- [@Eiskis](https://twitter.com/Eiskis)



## Setup

1. Download Servant
2. Unzip the downloaded package
3. Move files to a server

PHP 5.3 or newer is required, and Apache's `mod_rewrite` (or `rewrite_module`) must be enabled on the server.

Things should work out-of-the-box. You should see the demo site when you point your browser to where you put Servant.

Consult troubleshooting guide at [servantframework.com](http://servantframework.com/read/guides/troubleshooting) if you encounter any problems.



## Getting started

This is the basic file structure.

	backend/
		...
	site/
		pages/
			some-page.txt
			another-page/
				content.html
			...
		assets/
			behavior.js
			style.css
			...
		settings.json
	templates/
		html/
			html.php
			...
		...

As you might guess, you create pages and site content by adding `.txt`, `.html`, `.md` etc. files under the `site/pages/` folder. Pages are shown in generated menus, and have fancy URLs for users (e.g. `http://servant.com/read/<category>/<page>`).

You can use `site/settings.json` to define things like site name, description and favicon. `site/assets/` contains the stylesheets and JavaScripts of your site. Page-specific styles and scripts can be included under `site/pages/` as well.

Template files under `templates/` define the basic structure of your site. One template is used for your site, either the default or the one you define in settings.

Servant compiles all this into a functioning web site. Servant's files itself are located under `backend/`, but you shouldn't have to ever go there.

See full documentation at [servantframework.com](http://servantframework.com/).
