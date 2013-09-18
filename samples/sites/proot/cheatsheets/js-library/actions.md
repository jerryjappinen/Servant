
`p.actions` exposes backend functionality. These functions are used for things concerning [backend actions](?category=cheatsheets&id=actions).



## *p.actions.contact(parent, action, parameters)*

Call an action with arbitrary parameters. This sends a POST request and is used for shorthands.

Returns a *jQuery promise object*.



## *p.actions.get(parent, action, input)*

Call an action with a GET AJAX request. Remember that Proot actions behave the same way regardless of the HTTP method used.

Returns a *jQuery promise object*.



## *p.actions.link(parent, action, inputs)*

Output a URL that targets an action. These URLs are in domain-relative format. E.g.

	p.actions.link('images', 'show', ['logos/large.png', 240])
	// "/path/to/proot/use/appname/images/show/logos%252Flarge.png/240"



## *p.actions.map()*

Returns available actions for the current app. Returns a hash with all actions sorted by their parent, e.g.

	p.actions.link('images', 'show', ['logos/large.png', 240])

	// {
	//	images: ["list", "show"],
	//	web: ["client", "style"]
	// }



## *p.actions.post(parent, action, input)*

Call an action with a POST AJAX request. Remember that Proot actions behave the same way regardless of the HTTP method used.

Returns a *jQuery promise object*.
