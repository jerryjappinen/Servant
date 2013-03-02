
# App manifest

App manifest and everything in it is optional. Here's an example of a complete app manifest with all supported declarations included.



## Meta

#### `apps/appname/app.json`
	{

		"meta": {
			"name": "eiskis.net",
			"author": "Jerry Jäppinen",
			"description": "Jerry Jäppinen, Helsinki FI",
			"icon": "app.ico"
		},
		...
	}

Meta information outputted by the web client include `"meta"`, `"author"`, `"description"` and `"icon"`. The first three are just text, while the icon want a path to an icon or image file in your app package (this will be used as the favicon).



## Languages

#### `apps/appname/app.json`
	{
		...
		"languages": ["en", "es"],
		...
	}

List of supported languages in your app. Currently, this is only used for meta information.



## Actions

#### `apps/appname/app.json`
	{
		...
		"actions": [
			"web",
			"images",
			"files"
		],
		...
	}

List of actions allowed for this app. It is recommended to list the actions needed here in order to not block backend functionality that should not be used. If this is missing or is an empty array, all actions are available.



## Viewport

#### `apps/appname/app.json`
	{
		...
		"viewport": {

			"orientation": "auto",

			"width": "device-width",
			"min-width": "device-width",
			"max-width": "device-width",

			"height": "auto",
			"min-height": "auto",
			"max-height": "auto",

			"zoom": 1,
			"min-zoom": 1,
			"max-zoom": 1,
			"user-zoom": "zoom"

		},
		...
	}

**Note.** The example above includes all the supported attributes, but you should always write only what you need and let the browsers handle the rest.

Viewport definitions control how browsers, especially on mobile devices, scale your app when it's rendered on screen sizes of various dimensions and pixel densities. Different browsers require different ways of defining these rules, but Proot will handle creating the final code for all browsers based on what you write in your manifest.


The manifest uses `@viewport` style rules. Read more if you haven't already:

- [Introduction from Microsoft](http://msdn.microsoft.com/en-us/library/ie/hh708740(v=vs.85).aspx)
- [Introduction from Opera](http://dev.opera.com/articles/view/an-introduction-to-meta-viewport-and-viewport/)
- [Introduction at the Menacing Cloud blog](http://menacingcloud.com/?c=cssViewportOrMetaTag#wrapper)


## Tools

#### `apps/appname/app.json`
	{
		...
		"tools": [
			"http://twitterjs.googlecode.com/svn/trunk/src/twitter.min.js",
			"libraries/jquery.js",
			"baseline/",
			"prefixer/",
			"layers/",
			"proot/",
			"plugins/jquery.proot.js"
		],
		...
	}

List of libraries and plugins (CSS or JS) to load with your app. These are loaded from `tools/` or from external sources. You don't have to separate CSS assets from JS assets manually.



## Settings

#### `apps/appname/app.json`
	{
		...
		"settings": {
				"favoriteFood": "Kebab."
			}
		}
	}

This is just arbitrary content that will be available in `app.settings`. You can easily add your own custom settings or other content in your app manifest for whatever miscellaneous use.
