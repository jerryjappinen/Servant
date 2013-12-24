
# Servant development

## ???

? Sanitizing common JSON failures (e.g. trailing commas in lists) in setting files
? Add-on admin section
	- Settings file generation + maintenance
	- Status board
	- Online page editor



## Core

- Action-specific user settings
	- JSON (must stay secure on server)?  Allow users to declare action-specific configs in site settings?
	-> Private actions (not accessible via HTTP, only other scripts). E.g. database connection
- Data/storage/working directory services for actions
	- file traversal
	- adding files
	- making sure directory is available and writable
	- `ServantData`?
- Better (internal) URL scheme: use pseudo protocols to point to different locations
	- `servant://` (root)
	- `assets://`
	- `actions://`
	- `pages://`
	- `templates://`
	- In different contexts, one of these serves as the default root
	- Have one PHP methods that handle these conversions (so you can write something like `pointer('assets://foo')`)
	- Apply URL parsing to HTML form's action tag
	- Treat `../` as expected when parsing URLs
- `ServantTemplate` improvements
	- Support running Sevrant without templates (rendering HTML directly)
	- Make page optional for templates
	- Support selecting category node (pick page in template if necessary)
	- Pick a page in template or something, if needed (template can normalize `$node` with `pick()`)
- Save cache files for serialized raw input?
- Multiple pieces of content for templates
- Make `servant()->create()` more useful instead of relying on `nest` methods
- Search action (investigate full text search in HTML files)
- Case-insensitive `ServantNode` pointers
- Twig parser of `ServantFiles` should pass on treated variables
- Support multiple locations for templates, actions etc. (defined in `paths.php`)
	- Allows keeping site-specific templates under one directory
- Replace current action name mappings with a new system
	- Action IDs are always mapped via constants
	- Users see, in URLs, the names defined in `constants()->actions()`
- Pointer
	- Make usage more consistent (with pages, pointer means string and tree means what pointer is in input)



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



## Security

- What can I allow users to see?
- What do I allow users to see?
- `.htaccess` usage is error-prone
	- Too much separate files
	- Easy to omit when moving files via GUI



## Status board

Some kind of status page that indicates if things like paths are in order, what settings are active etc. Helps during installation and warns about common pitfalls.

- Report cards structure
- Test routines separated from main thingy



## Out-of-lab testing

The system needs to be properly tested on various environments.

- Different PHP versions
- Different Apache configurations (especially when missing `rewrite_module`)
- Any other differences between environments
	- Common things
	- Invisible things



## Performance

- Find general bottlenecks, improve
- Can I use ReactPHP?
	- Investigate, try out
	- Attempt to provide a version, with minimum differences, that takes advantage of React
