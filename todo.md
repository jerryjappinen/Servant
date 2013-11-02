
# Servant development

## Core

- Multiple pieces of content for nested templates
- Input system
	- Accept GET, POST and other input through wrapper
	- Merge all input in ServantInput
	- Allow actions to request certain kinds of input
	- Validate input based on the demands of an action



## Status board

Some kind of status page that indicates if things like paths are in order, what settings are active etc. Helps during installation and warns about common pitfalls.

- Report cards structure
- Test routines separated from main thingy



## Out-of-lab testing

The system needs to be properly tested on various environments.

- Different PHP versions (from 5.2 up)
- Different Apache configurations (especially when missing `rewrite_module`)
- Any other differences between environments
	- Common things
	- Invisible things



## Security audit

- What can I allow users to see?
- `.htaccess` usage is error-prone
	- Too much separate files
	- Easy to omit when moving files via GUI
