
# Write your first web app

#### **Continue only when you [have Proot up and running](?category=tutorials&id=setup-walkthrough).**

So: Proot takes an *app package*, which is a directory under `apps/` and delivers its contents to a browser. App package contains HTML, CSS and JS files, and other assets like images, like any web app. So that's what we're going to create!



## Create the app package

Create a new directory under `apps/`, with a name of your choosing. This will be the ID of your app (visible in the URL), so **don't use spaces, underscores, dashes or other special characters**.

You can now open the app in your browser ([http://localhost/path/to/proot/yourapp/](http://localhost/path/to/proot/yourapp/)). You should see a blank page: your app doesn't actually do anything beyond existing, since you didn't write any code &ndash; that's what you'd expect right?

If you mistyped the name of your app in the URL, you should see an error message telling you which apps are available. You can try that too &ndash; that's what Proot's error pages look like.



## Add a base template

You probably want to add some HTML! Create any `.html` file in the root of your app package, and write something like this in it.

#### `apps/yourapp/whatever.html`
	<h1>This is my app</h1>
	<p>And its name is foo</p>
	<p>And it runs on <a href="http://eiskis.net/proot/">Proot</a></p>

Now, if you reload the app, the above HTML will be printed out. Very straightforward.

If you look at page source, you can see that Proot added HTML headers, but `<body>` is all you.



## Add styles

Again, simply add any `.css` file to the root:

#### `apps/yourapp/whatever.css`
	body {
		margin: 0 auto;
		padding: 5%;
		max-width: 35em;
		background-color: #212626;
		color: #f3f2f2;
		font-family: sans-serif;
	}
	a {
		color: #0080bf;
	}

Now, if you reload the app, you have the same template as before, but it's not as ugly as your browser made it look like.

If you look at page source, you'll see that the document refers to an external stylesheet. Open it, and you'll find your styles.



## Add a script

Continuing on the same path, after structure and styles comes behavior. Proot encourages you to write living interfaces, not just static pages! Start by adding a file named `app.js` to the root of your app package and write some JavaScript:

#### `apps/yourapp/app.js`
	alert('My app makes alerts :<');

This is your app's *launch method*. What you write here is executed when a user loads your app in a browser. So now you'll be seeing an annoying alert popup every time you load the app.

Proot gives us the `app` object that you can use in your scripts. So let's do something less annoying with it:

#### `apps/yourapp/app.js`
	console.log(app);

Now you will see the object turn up in the developer console of your browser, where you can investigate its contents. For example, each app has an ID (the name of the app directory) which is included as `app.id`. If you want the alerts back, you can write:

#### `apps/yourapp/app.js`
	alert(app.id);

...to prompt the user with the name of your app, whatever that is.

**Note:** If you're used to `window.onload()` or `$(document).ready()`, you do not have to write that here. Proot will wrap your code into a launch method and execute it after the document has loaded for you.



### Plugins and libraries

You probably want to use some JS libraries like jQuery, stylesheets packs or other plugins in your apps. This is very easy, but first we need something we don't have yet.



## App manifest

`app.json` is a file in your app directory's root that includes meta information and settings as well as declares external plugins to be loaded. To add the usual things, create the file:

#### `apps/yourapp/app.json`
	{
		"meta": {
			"name": "Your app",
			"description": "This is the app that makes alerts",
			"author": "You",
			"icon": "app.ico"
		},
		"tools": [
			"libraries/jquery.js"
		],
		"settings": {
			"favoriteFood": "Kebab."
		}
	}

Here's what you did:

- You gave your app proper meta information, and also a favicon &ndash; add `app.ico` to your app package to see it.
- You told Proot what tools you want to use from `tools/`. You can add more files or directories later, and also external URLs.
- You also added custom settings. Now you can use `app.settings.favoriteFood` in `app.js`.

Remember, this needs to be valid JSON, but Proot will give you an error of you mess it up.



## Recap

So, now you should have:

#### `apps/yourapp/`

	app.js
	app.json
	whatever.css
	whatever.html

And in your browser you'll see pretty much what you'd expect:

- Meta information is appropriately added to within the HTML document's `<head>`.
- Your *structure* appears within the HTML document's `<body>`.
- Browser gets whatever styles you defined, as one stylesheet.
- Your scripts are executed when the page is loaded.
