
# Servant

- Intro, download & docs
	- [eiskis.net/servant](http://www.eiskis.net/servant/)
- Source & issues
	- [bitbucket.org/Eiskis/servant](http://bitbucket.org/Eiskis/servant/)
- By Jerry Jäppinen
	- Released under LGPL
	- [eiskis@gmail.com](mailto:eiskis@gmail.com)
	- [eiskis.net](http://eiskis.net/)
	- [@Eiskis](https://twitter.com/Eiskis)



## Setup

1. Download Servant
2. Unzip the download on a server with PHP 5.2 or newer (HAML and Twig support require 5.3+)
3. Make sure `mod_rewrite` or `rewrite_module` is enabled the server.

Things should work out-of-the-box. You should see the demo site when you point your browser to where you put Servant.

Consult troubleshooting guide at [eiskis.net/servant](http://eiskis.net/servant/site/get-started/installation/) if you encounter any problems.



## Getting started

This is the basic file structure.

	backend/
		...
	site/
		content/
			some-page.txt
			another-page/
				content.html
			...
		theme/
			behavior.js
			style.css
			...
		settings.json
	templates/
		default/
			header.php
			...
		...

As you might guess, you create pages and site content by adding `.txt`, `.html`, `.md` etc. files under the `content/` folder.

You can use the (optional) site settings file `settings.json` to define things like site name, description and favicon. `theme/` contains the CSS and JavaScript assets for the look and behavior of your site. Page-specific styles and scripts can be included under `content/` as well.

Template files under `templates/` define the basic structure of your site. One template is used for your site, either the default or the one you define in settings.

Servant compiles all this into a functioning web site. Servant's files itself are located under `backend/`, but you shouldn't have to ever go there.

See full documentation at [eiskis.net/servant](http://www.eiskis.net/servant/).
