
Proot's backend functionality is available in bite-sized *actions*. Showing an image, uploading a file, sending an email or reading something from the database are some examples. You use the actions with AJAX requests.

- [Cheatsheet of available actions](?category=cheatsheets&id=actions)
- [Creating new actions](?category=actions)



## Server communication

When you're communicating with a server, you always have to deal with an unpredictable lag. If you're handling server requests with Proot's servable methods or jQuery's AJAX methods, you should make sure you know how to handle jQuery's deferred objects. They let you easily write scripts that are executed only after you have received a response from the server.



### Sending requests

Proot gives you premade AJAX functions for all actions. Type `proot.actions` in your browser's developer console for a list of commands. These functions behave like jQuery's AJAX functions. You can also use jQuery's stock AJAX methods to contact them if you want, just like any other web service.

#### `views/page/home/prepare.js`
	proot.act.images.list('images/backgrounds/').then(function (response) {
		proot.dev.log(response);
	});

These actions can also be accessed simply with a browser at `use/appname/actionparent/actionname/`. You can get valid, properly escaped URLs with `proot.link`:

#### `views/page/home/prepare.js`
	view.dom.titleImage.populate(proot.link.images.show('images/thumbnails/eiskis.jpg'));

Actually, even when Proot outputs your app to the user, it's actually using the *web client* action.



## Contacting other services

Proot apps aren't in any kind of special symbiosis with the Proot backend when they're running in the browser. You can contact any web service that responds to HTTP requests just like you contact the Proot backend. An external service needs to support CORS, which is beginning to be more and more common.
