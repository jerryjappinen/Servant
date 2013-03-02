
# Plugins

You can include JS libraries, predefined CSS style packages, jQuery plugins etc. in your app by just adding them to the manifest file. You can include anything that you have in `tools/`. You can also use files from CDNs by using external URLs in the manifest.

These shared tools are available in all apps. Also, any files from the `tools/` folder in your app package are included automatically.



## Prerequisites

These tools must be included in your `app.json` for you to be able to write a Proot-style web app. Your manifest file always accurately reflects what's actually loaded when your app is run. In short, to kickstart a Proot-style app, you should have the following tools included:

#### `app.json`
	{
		...

		"tools": [

			"libraries/jquery.js",
			"baseline/",
			"proot/"

			"plugins/jquery.history.js",
			"plugins/jquery.proot.js",

		]
	}

You can write web sites and apps on Proot without these, even without any libraries, but this is what Proot-style apps use by default.



### jQuery

Read all about it at <a href="http://jquery.com/" target="_blank">jquery.com</a> if you're not familiar.



### Baseline

Nifty set of low-level helpers for sane people. Stuff like type checking, listing and finding keys and values, trimming strings and counting lengths of objects are no longer a pain.

Type `bl` in your browser's developer console to check it out. Read more online at <a href="http://eiskis.net/proot/baseline/" target="_blank">eiskis.net/baseline</a>.



### Proot JS library

This is where Proot's view handling, backend communication and a DOM handling functionality lies. The JS library is focuses on normalization and common low-level operations. It's there to help you handcraft great, custom interface behavior, not write it for you.

[See the reference](?category=cheatsheets&id=js-library) or type `proot` or `p` in your browser's developer console to test it out.



### Optional

<a href="https://github.com/balupton/History.js/" target="_blank">History.js</a> is required if you use history states.

The [Proot jQuery plugin](?category=cheatsheets&id=jquery-plugin) can be used for convenience when using Proot functions.



## jQuery plugins

Many jQuery plugins try to do everything for you, from event bindings and DOM manipulation to AJAX requests and callbacks. In Proot, there are existing solutions for handling these, and you get full control of your interface: you probably don’t want to use big wrapper functions in big plugins.

For example, if you want to use a popup plugin in a Proot app, a plugin like Fancybox is an overkill as it implements its own view management. Many other plugins work similiarly. You probably want to extract event bindings to one of your generic initialization functions, and provide the UI functionality as a plugin. That way, you can initialize Proot’s views in a popup provided by a plugin.
