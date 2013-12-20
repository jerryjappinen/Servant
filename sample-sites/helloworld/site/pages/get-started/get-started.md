
# Hello world!

You're now running Servant - a practical and approachable micro framework for human web developers. Read more at [servantframework.com](http://servantframework.com/).

If your setup seems to be broken, there's a troubleshooting guide at [servantframework.com](http://servantframework.com/read/guides/troubleshooting).



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



## Editing pages

As you might guess, you create pages and site content by adding `.txt`, `.html`, `.md` etc. files under the `pages/` folder. Pages are shown in generated menus, and have fancy URLs for users (e.g. `http://servant.com/read/<category>/<page>`).

Template files under `templates/` define the basic structure of your site. One template is used for your site, either the default or the one you define in settings.

[More about pages](http://servantframework.com/read/guides/pages/)



## Editing site settings

You can use `settings.json` to define things like site name, description and favicon.

[More about site settings](http://servantframework.com/read/guides/site-settings/)



## Writing styles and scripts

`assets/` contains the stylesheets and JavaScripts of your site. Page-specific styles and scripts can be included under `pages/` as well.

LESS and SCSS are supported out-of-the-box.

[More about assets](http://servantframework.com/read/guides/assets/)
