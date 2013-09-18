
This library is the heart of any Proot-style JavaScript app. It includes view and state handling, sane DOM manipulation and more. Include it in your [app manifest](?category=apps&id=manifest) and you're all set to use it in your app's script files.

The library is split into understandable namespaces (each in their own files). It's possible to include only the namespaces you need, as long as internal dependencies are satisfied.






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



