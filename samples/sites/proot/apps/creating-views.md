
Proot is view-oriented, and Proot apps consist of *views*. You can write separate templates, styles and behavior for each view, and Proot will apply them when the views are used in the app.

You should always begin writing an app by breaking your design down to collections of views. What kind of view types does your app have? Think pages, modal dialogs, notifications, sidebars and so on. Within those types, you can still create multiple different views with their own templates, scripts and styles.



## View lifecycle

Views have a *lifecycle*: they're prepared and rendered and then, later, killed and unrendered. You can add separate `JS` files that correspond to these to build upp your application.

For each lifecycle event, you can create behavior on the app level, view type level or view level. So, when a view is loaded, the code in the following files is run:

1. `views/prepare.js`
2. `views/:type/prepare.js`
3. `views/:type/:view/prepare.js`
4. `views/render.js`
5. `views/:type/render.js`
6. `views/:type/:view/render.js`

And, when a view is killed, we do the same thing in reversed order.

1. `views/:type/:view/unrender.js`
2. `views/:type/unrender.js`
3. `views/unrender.js`
4. `views/:type/:view/kill.js`
5. `views/:type/kill.js`
6. `views/kill.js`

In practice this means that you can create both very generic and very customized behavior for your views. In the future more lifecycle events will be supported, for example stashing and reviving (so you can e.g. pause some behavior for when a view is inactive but not dead). You can already add more methods and run them, but the internal state handling won't run them for automatically.

You can also create a multipurpose scripts:

#### `views/layout/page/`
	prepare.js
	prepare-update.js
	update.js

The contents of `prepare-update.js` will be run on preparation *and* on update. `prepare.js` and `update.js` will also be run as before.



## Writing view behavior

So you write behavior for your views in the lifecycle. You can manipulate and populate the DOM, create timeouts and anything else you can think off with JavaScript.

You often want to report a view method complete only until after e.g. an animation has been executed. To do this, *resolve* the view's status manually. For example:

#### `views/page/render.js`
	// Progress to the next method only after the animation is complete
	$('h1').fadeIn(200, function () {
		view.status.resolve();
	})

In effect, the execution of the next script in queue will be deferred by *200 ms*. This allows you, for example, to render elements out gracefully before destroying them when a view dies.



### What's available

In each view lifecycle method (on any level), you can use the following variables:

- `app`: Information of the currently running app
	- `actions`
	- `container`
	- `id`
	- `launch`
	- `life`
	- `lock`
	- `memory`
	- `meta`
	- `paths`
	- `settings`
	- `shared`
	- `state`
	- `transitions`
	- `url`
	- `wrapper`
- `shared`: All shared code from your app package
- `view`: Information about the currently running view
	- `container` (jQuery object)
	- `content`
	- `dom` (list of jQuery objects)
	- `name`
	- `selectors`
	- `status` (deferred object)
	- `type`
	- `wrapper` (jQuery object)
