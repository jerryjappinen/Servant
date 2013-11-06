
# Servant development

## Core

- Preserve variables when reading multiple dynamic template files (use `run_scripts`)
- `ServantTheme` should not be a service
- `templates()->available()`
- Nestable actions
	- `$action->nest()`
	- `$template->action()`
	- Action configuration should be supported
		- Authors can declare actions private: not accessible via HTTP, only other scripts
		- JSON? Make sure JSON stays secure on server!
		- Allow users to declare action-specific configs in site settings?
- Remove `pages()->current()`
	- A `$page` should be passed to
		- actions
		- templates
- Multiple pieces of content for nested templates
- Data/storage/working directory for actions
	- Should be under `site/` as it's site-specific
- Input system
	- Accept GET, POST and other input through wrapper
	- Merge all input in ServantInput
	- Pass input to actions
	- Allow actions to declare input demands (for validation)
	- Validate input based on the demands of an action



## Sample projects / project templates

- Core
	- Absolutely nothing but the core release
- Barebones
	- Placeholder files and settings
	- HTML template
	- For building custom sites
- Simple
	- A couple of placeholder pages
	- A simple but attractive theme
	- Possibly a JQuery plugin or two to show how they're used
- AJAX site
	- Use HTML head in the home page
	- Load pages via AJAX
	- Include simple view architecture in JS
	- All styling in theme
	- Potentially include additional actions (like "page" action that outputs pages without a template)



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
