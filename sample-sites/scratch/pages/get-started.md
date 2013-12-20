
# Hello world!

You're now running Servant - a practical and approachable micro framework for human web developers. Read more at [servantframework.com](http://servantframework.com/).

If your setup seems to be broken, there's a troubleshooting guide at [servantframework.com](http://servantframework.com/read/guides/troubleshooting).



## Getting started

This is the basic file structure of your site.

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
