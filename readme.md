
# Servant 0.2.0

Servant is a practical and approachable micro framework for web developers, designed for us humans who prefer understandable environments and frequently whip up new web sites.

- Intro, download & docs
	- [servantframework.com](http://servantframework.com/)
- Source & issues
	- [github.com/Eiskis/Servant](https://github.com/Eiskis/Servant/)
- By Jerry Jäppinen
	- Released under LGPL
	- [eiskis@gmail.com](mailto:eiskis@gmail.com)
	- [eiskis.net](http://eiskis.net/)
	- [@Eiskis](https://twitter.com/Eiskis)



## Setup

1. [Download Servant](https://github.com/Eiskis/Servant/archive/master.zip)
2. Unzip the downloaded package
3. Move files to a server

PHP 5.3 or newer is required, and Apache's `mod_rewrite` (or `rewrite_module`) must be enabled on the server.

Things should work out-of-the-box. You should see the demo site when you point your browser to where you put Servant.

See full documentation at [servantframework.com](http://servantframework.com/) if you encounter any problems.



## Getting started

This is the basic file structure.

	assets/
		behavior.js
		style.css
		...
	backend/
		...
	pages/
		some-page.txt
		another-page/
			content.html
		...
	templates/
		html/
			html.php
			...
		...
	settings.json

Servant compiles all this into a functioning web site. Servant's files itself are located under `backend/`, but you shouldn't have to go there unless you want to extend the backend functionality.



#### Editing pages

As you might guess, you create pages and site content by adding `.txt`, `.html`, `.md` etc. files under the `pages/` folder. Pages are shown in generated menus, and have fancy URLs for users (e.g. `http://servant.com/read/<category>/<page>`).

Template files under `templates/` define the basic structure of your site. One template is used for your site, either the default or the one you define in settings.

[More about pages](http://servantframework.com/read/guides/pages/)



#### Editing site settings

You can use `settings.json` to define things like site name, description and favicon.

[More about site settings](http://servantframework.com/read/guides/site-settings/)



#### Writing styles and scripts

`assets/` contains the stylesheets and JavaScripts of your site. Page-specific styles and scripts can be included under `pages/` as well.

LESS and SCSS are supported out-of-the-box.

[More about assets](http://servantframework.com/read/guides/assets/)
