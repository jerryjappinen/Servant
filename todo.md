
# Servant development

## Needs refactoring (but works)

- `ServantPages` is a mess
- Action and page objects should probably be generated in `ServantResponse`, *not* `ServantMain`
- Use `ServantAvailable()` for actions, templates, utilities, pages?
- Use `ServantGenerator` instead of `$this->generate`?
- `ServantInput` is more like `ServantSelected` or something, just a helper for Main instead of actual input object
	- Should not include action or page selection either



## Core

- Multiple pieces of content for templates
- `ServantTheme` should not be a service
- Better (internal) URL scheme: use pseudo protocols to point to different locations
	- `servant://`
	- `actions://`
	- `templates://`
	- `pages://`
	- `theme://`
	- In different contexts, one of these serves as the default root
- `ServantPage` should not include URL manipulation
- Input system
	- Store input for Main in `ServantInput`
	- Interpret and merge input of different types
	- Allow actions to declare input demands (for validation)
	- Validate input based on the demands of an action
	- Pass validated input to actions
- Action configuration
	- Authors can declare actions private: not accessible via HTTP, only other scripts
	- JSON? Make sure JSON stays secure on server!
	- Allow users to declare action-specific configs in site settings?
- Preserve variables when reading multiple dynamic template files (not just PHP files like in actions)
- Data/storage/working directory for actions
	- Should be under `site/` as it's site-specific



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
