
# Servant

- Intro, download & docs
	- http://www.eiskis.net/servant/
- Source & issues
	- http://bitbucket.org/Eiskis/servant/
- By Jerry Jäppinen
	- Released under LGPL
	- eiskis@gmail.com
	- http://eiskis.net/
	- @Eiskis



## Setup

1. Download Servant
2. Unzip the download on a server with PHP 5.2 or newer (HAML and Twig support require 5.3+)
3. Make sure `mod_rewrite` or `rewrite_module` is enabled the server.

Things should work out-of-the-box. You should see the demo site when you point your browser to where you put Servant.

Consult troubleshooting guide at [eiskis.net/servant](http://www.eiskis.net/servant/) if you encounter any problems.



## Getting started

This is the basic file structure.

	backend/
		...
	pages/
		some-page.txt
		another-page/
			content.html
		...
	template/
		template.php
		...
	theme/
		behavior.js
		style.css
		...
	settings.json

As you might guess, you create pages and site content by adding .txt, .html, .md etc. files under the "pages/" folder. You can use "site/settings.json" to define things like site name, description and favicon. "theme/" contains

Servant compiles all this into a functioning web site via the template files under "template/".

3. Template and theme to define how the structure and look, respectively.
4. Styles and scripts under pages/ will also be loaded.

Full documentation at http://www.eiskis.net/servant/.
