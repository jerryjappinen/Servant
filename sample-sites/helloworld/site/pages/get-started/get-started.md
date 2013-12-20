
# Hello world!

You're now running Servant - a practical and approachable micro framework for human web developers. Read more at [servantframework.com](http://servantframework.com/).

If your setup seems to be broken, there's a troubleshooting guide at [servantframework.com](http://servantframework.com/read/guides/troubleshooting).



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

Servant compiles all this into a functioning web site. Servant's files itself are located under `backend/`, but you shouldn't have to go there unless you want to extend the backend functionality.



## Editing pages

As you might guess, you create pages and site content by adding `.txt`, `.html`, `.md` etc. files under the `site/pages/` folder. Pages are shown in generated menus, and have fancy URLs for users (e.g. `http://servant.com/read/<category>/<page>`).

Template files under `templates/` define the basic structure of your site. One template is used for your site, either the default or the one you define in settings.

[More about pages](http://servantframework.com/read/guides/pages/)



## Editing site settings

You can use `site/settings.json` to define things like site name, description and favicon.

[More about site settings](http://servantframework.com/read/guides/site-settings/)



## Writing styles and scripts

`site/assets/` contains the stylesheets and JavaScripts of your site. Page-specific styles and scripts can be included under `site/pages/` as well.

LESS and SCSS are supported out-of-the-box.

[More about assets](http://servantframework.com/read/guides/assets/)
