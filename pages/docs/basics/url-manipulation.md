
# URLs in stylesheets

URLs in CSS and HTML are manipulated on the final site, as they must point to the right place on the rendered page. Here are some examples:



### Absolute URLs

Absolute URLs will not be touched.



### Relative URLs

Relative URLs point to the location of the file they're used in.

For example, if in our CSS we write

##### assets/layout/header.css
	#header {
		background-image: url('header.jpg');
	}
	h1 {
		background-image: url('logos/logo.png');
	}

the result in the minified stylesheet file will be

##### http://www.yoursite.com/stylesheets/
	#header {
		background-image: url('/path/to/servant/assets/layout/header.jpg');
	}
	h1 {
		background-image: url('/path/to/servant/assets/layout/logos/logo.png');
	}



### Root-relative URLs

Root-relative URLs point to the root of the site or assets folder:

##### assets/layout/footer.css
	#footer {
		background-image: url('/footer.jpg');
	}
	.footer-logo {
		background-image: url('/assets/logo.png');
	}

the result in the minified stylesheet file will be



##### http://www.yoursite.com/stylesheets/
	#footer {
		background-image: url('/path/to/servant/assets/footer.jpg');
	}
	h1 {
		background-image: url('/path/to/servant/assets/assets/logo.png');
	}



### URLs to actions

You don't usually need to write links to specific actions, but it can be done. You might need it if you created an action for scaling images, for example, and wanted to use scaled images in your stylesheets. Use **two forwards slashes** in the beginning of the URL, and write the name of the action first:

##### assets/layout/body.css
	.container-body {
		background-image: url('//imagescale/body.jpg/200/200');
	}

the result in the minified stylesheet file will be



##### http://www.yoursite.com/stylesheets/
	.container-body {
		background-image: url('/path/to/servant/sitename/imagescale/body.jpg/200/200');
	}
