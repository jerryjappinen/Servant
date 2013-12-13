
# Servant development

## ???

? Sanitizing common JSON failures (e.g. trailing commas in lists) in setting files
? Add-on admin section
	- Settings file generation + maintenance
	- Status board
	- Online page editor



## Core

- Input system (for actions)
	- Add serialization to `ServantInput`
	- Save cache files for (serialized) input
	- Pass arbitrary GET from fancy URLs to actions properly
- Stop using `__call`
- Rename `ServantSettings` to `ServantConstants`
- Support multiple locations for templates, actions etc. (defined in `paths.php`)
	- Allow's to keep site-specific templates under one directory
- Make page optional for templates
- Action configuration
	- JSON (must stay secure on server)?  Allow users to declare action-specific configs in site settings?
	-> Private actions (not accessible via HTTP, only other scripts). E.g. database connection
	-> Input declarations
- Better (internal) URL scheme: use pseudo protocols to point to different locations
	- `servant://` (root)
	- `assets://`
	- `actions://`
	- `pages://`
	- `templates://`
	- In different contexts, one of these serves as the default root
- Data/storage/working directory services for actions
	- file traversal
	- adding files
- Multiple pieces of content for templates
- Support selecting category node
	- Pick a page in template or read action or something when needed
- Search action (investigate full text search in HTML files)
- Twig parser of `ServantFiles` should pass on treated variables
- Case-insensitive `ServantNode` pointers
- Arbitrary content types
	- Allow action to set the content type directly
	- Store full content type in cache file name



## Bugs

- Page pointers are weird if there is only one directory under subfolder



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
