
# URLs in stylesheets

URL calues used in CSS are manipulated so that they point to the right place on the final page with a single merged stylesheet file.

Here are some examples of how the URLs will change.



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

Root-relative URLs point to the root of the theme folder:



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



### Absolute URLs

Absolute URLs will not be touched.
