
# Backend actions

Servant is easily extendable via the built-in action system. Actions are backend code snippets that generally respond to HTTP requests. They're ideal for quickly setting up custom functionality to support your frontend, for example responding to AJAX requests. Example uses include proxys and database access.

Servant automatically exposes all actions via dedicated URLs. The core release includes uses actions for outputting things like minified stylesheets and sitemaps.



## How they work

Each directory under `backend/actions/` is an action. All the script files of an action are executed in alphabetical order, recursively.

Generally, an action does what it does based on user input, and then responds with an HTTP status code and some output.

<p><a href="/docs/components/action">Full documentation</a></p>



## What they're not for

Actions are not a full-fledged solution for your app's business logic. They're created to support the frontend, and meant to be accessible for developers.

If you're creating a complex web app, consider creating the business logic from the frontend as a separate program and communicating to clients via APIs.



## Variables available for scripting

The following variables provide access to Servant's internals:

Variable      | Description                                | Read more                                    |
------------- | ------------------------------------------ | -------------------------------------------- |
`$servant`    | Main services provided by Servant.         | [ServantMain](/docs/components/main)         |
`$input`      | Access to *pointer* and *validated input*. | [ServantInput](/docs/components/input)       |
`$action`     | The current action.                        | [ServantAction](/docs/components/action)     |



## Example

##### File structure

	backend/
		actions/
			myaction/
				prepare.php
				output.php

##### ...myaction/prepare.php

	// Set some variables
	$myvar = '';
	if ($servant->debug()) {
		$myvar = '<p>Bar</p>';
	}

##### ...myaction/output.php

	// Variables are available in all files
	$action->output($myvar);
	
	// Chainable methods to set content type, status and output
	$action->output($myvar)->contentType('html')->status(200);

	// Show error if output is empty
	if (!$action->output()) {
		$action->contentType('text')->status(500)->output('Something went wrong.');
	}
