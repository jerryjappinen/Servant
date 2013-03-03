
# Proot JS library

This library is the heart of any Proot-style JavaScript app. It includes view and state handling, sane DOM manipulation and more. Include it in your [app manifest](?category=apps&id=manifest) and you're all set to use it in your app's script files.

The library is split into understandable namespaces (each in their own files). It's possible to include only the namespaces you need, as long as internal dependencies are satisfied.







## p.actions

Exposed backend functionality. These functions are used for things concerning [backend actions](?category=cheatsheets&id=actions).



#### `contact(parent, action, parameters)`

Call an action with arbitrary parameters. This sends a POST request and is used for shorthands.

Returns a *jQuery promise object*.



#### `get(parent, action, input)`

Call an action with a GET AJAX request. Remember that Proot actions behave the same way regardless of the HTTP method used.

Returns a *jQuery promise object*.



#### `link(parent, action, inputs)`

Output a URL that targets an action. These URLs are in domain-relative format. E.g.

	p.actions.link('images', 'show', ['logos/large.png', 240])
	// "/path/to/proot/use/appname/images/show/logos%252Flarge.png/240"



#### `map()`

Returns available actions for the current app. Returns a hash with all actions sorted by their parent, e.g.

	p.actions.link('images', 'show', ['logos/large.png', 240])

	// {
	//	images: ["list", "show"],
	//	web: ["client", "style"]
	// }



#### `post(parent, action, input)`

Call an action with a POST AJAX request. Remember that Proot actions behave the same way regardless of the HTTP method used.

Returns a *jQuery promise object*.






## p.app

Information about the currently running app.

#### `get()`

The app object.



### p.app.memory

#### `memory.get()`

Return a specific item from the app's memory hash, or the whole hash.






## p.contact

Includes shorthands for calling each backend action with an AJAX request. They call `p.actions.execute()`, and so return jQuery promise objects (like all AJAX functions in Proot). They follow this example:

	p.actions.contact('images', 'list', ['logos/'])
	p.contact.images.list('logos/')

Both will return a jQuery promise object, with an array of image file names as incoming response.






## p.dom

Easy DOM handling to supplement jQuery and normalize HTML element peculiarities.




#### `detect(element)`

Find out the type of the DOM element.




#### `convert()`



#### `distribute()`



#### `extract(element)`



#### `fill()`



#### `populate()`



#### `select(type, container)`



### p.dom.is

Get status information of a jQuery DOM element.



#### `types.disabled(element)`



#### `types.enabled(element)`



#### `types.hidden(element)`



#### `types.visible(element)`



### p.dom.to

Change status of a jQuery DOM element.



#### `types.disabled(element)`



#### `types.enabled(element)`



#### `types.hidden(element)`



#### `types.visible(element)`



### p.dom.types



#### `types.list()`

List internal UI element types.



#### `types.map()`

Return a hash of internal UI element types mapped to their corresponding HTML element selectors.




## p.form






## p.html






## p.link

Shorthands for each available action to call `p.actions.link()` easily, following this example:

	p.actions.link('images', 'show', ['logos/large.png', 240])
	p.link.images.show('logos/large.png', 240)

Both will return `"/path/to/proot/use/appname/images/show/logos%252Flarge.png/240"`.







## p.normalize






## p.paths






## p.state






## p.text






## p.viewport






## p.views



