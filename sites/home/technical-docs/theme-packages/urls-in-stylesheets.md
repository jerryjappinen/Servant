
# URLs in stylesheets

URL calues used in CSS are manipulated so that they point to the right place on the final page with a single merged stylesheet file. URLs work in the same manner in both site and theme packages.

Here are some examples of how the URLs will change.



### Absolute URLs

Absolute URLs will not be touched.



### Relative URLs

Relative URLs point to the location of the file they're used in.

For example, if in our CSS we write

##### themes/default/layout/header.css
	#header {
		background-image: url('header.jpg');
	}
	h1 {
		background-image: url('logos/logo.png');
	}

the result in the minified stylesheet file will be

##### sitename/stylesheets/
	#header {
		background-image: url('/path/to/servant/themes/default/layout/header.jpg');
	}
	h1 {
		background-image: url('/path/to/servant/themes/default/layout/logos/logo.png');
	}



### Root-relative URLs

Root-relative URLs point to the root of the site or theme folder:

##### themes/default/layout/footer.css
	#footer {
		background-image: url('/footer.jpg');
	}
	.footer-logo {
		background-image: url('/assets/logo.png');
	}

the result in the minified stylesheet file will be



##### sitename/stylesheets/
	#footer {
		background-image: url('/path/to/servant/themes/default/footer.jpg');
	}
	h1 {
		background-image: url('/path/to/servant/themes/default/assets/logo.png');
	}



### URLs to actions

You don't usually need to write links to specific actions, but it can be done. You might need it if you created an action for scaling images, for example, and wanted to use scaled images in your stylesheets. Use **two forwards slashes** in the beginning of the URL, and write the name of the action first:

##### themes/default/layout/body.css
	.container-body {
		background-image: url('//imagescale/body.jpg/200/200');
	}

the result in the minified stylesheet file will be



##### sitename/stylesheets/
	.container-body {
		background-image: url('/path/to/servant/sitename/imagescale/body.jpg/200/200');
	}
