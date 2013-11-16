
# Servant development

## Needs refactoring (but works)

- General flow
	- Action objects should be generated in `ServantResponse`, *not* `ServantMain`
	- `Page` should be selected by actions based on input
	- `ServantInput` Should not include action or page selection either
		- It's not `ServantSelected`, but now it serves as just a helper for Main instead of actual input object



## Core

- Improve usability of `ServantPage` and `ServantCategory`
- Add pages to `ServantAvailable`
- If PHP scripts are separated from template formats (as they are in actions now), add the file extension to constants
- Search action (investigate full text search in HTML files)
- Multiple pieces of content for templates
- Better (internal) URL scheme: use pseudo protocols to point to different locations
	- `servant://` (root)
	- `assets://`
	- `actions://`
	- `pages://`
	- `templates://`
	- In different contexts, one of these serves as the default root
- Input system
	- Store input for Main in `ServantInput`
	- Interpret and merge input of different types
	- Allow actions to declare input demands (for validation)
	- Validate input based on the demands of an action
	- Pass validated input to actions
- Allow disabling cache in actions
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
	- All styling in theme (or include dynamic stylesheet/script injections)
	- Potentially include additional actions (like "page" action that outputs pages without a template)



## Status board

Some kind of status page that indicates if things like paths are in order, what settings are active etc. Helps during installation and warns about common pitfalls.

- Report cards structure
- Test routines separated from main thingy



## Out-of-lab testing

The system needs to be properly tested on various environments.

- Different PHP versions (from 5.2 up?)
- Different Apache configurations (especially when missing `rewrite_module`)
- Any other differences between environments
	- Common things
	- Invisible things



## Performance

- Find general bottlenecks, improve
- Can I use ReactPHP?
	- Investigate, try out
	- Attempt to provide a version, with minimum differences, that takes advantage of React



## Security

- What can I allow users to see?
- `.htaccess` usage is error-prone
	- Too much separate files
	- Easy to omit when moving files via GUI
