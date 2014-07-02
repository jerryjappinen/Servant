
# Servant development

## ???

- Sanitizing common JSON failures (e.g. trailing commas in lists) in setting files
- Add-on admin section
	- Settings file generation + maintenance
	- Status board
	- Online page editor



## Core

- Possibly offer `->root()` convenience getter in `ServantMain`
- Action's init behavior is weird and not very handy
	- Take in pointer and other input separately
- Redirects
	- Set redirects paths in `settings.json`
	- 301 redirect to the address provided
	- Overrides action and page selection etc.
- action-specific cache times, respect node-specific cache settings in site action
	0. only site-wide browser and server cache times are supported in `ServantResponse` now
	1. Actions can set browser **and** server cache times
	2. node-specific actions respect node-specific cache settings
- In `html` template, send page pointer parameters to page-specific external scripts/stylesheets that point to local Servant actions?
- Support `json` settings files in site assets, node-specific paths and templates
	- JSON file contents outputted as JS hashes
	- Available like scripts and stylesheets
	- `json` reader and parser available globally (now in `ServantManifest` only)
- `ServantPath`
	- Returned by all path properties
	- `__toString`
	- `->plain()`
	- `->domain()`
	- `->url()`
	- `->server()`
	- `->split()`
	- `->foo('more', 'url', 'parameters')`
- Better (internal) URL scheme: use pseudo protocols to point to different locations
	- `servant://` (root)
	- `assets://`
	- `actions://`
	- `pages://`
	- `templates://`
	- In different contexts, one of these serves as the default root
	- Have one PHP method that handles these conversions (so you can write something like `pointer('assets://foo')`)
	- Apply URL parsing to HTML form's action tag
	- Treat `../` as expected when parsing URLs
- Support multiple locations for templates, actions etc. (defined in `paths.php`)
	- Allows keeping custom templates and actions under one directory
	- Makes updating Servant easier
- `ServantTemplate` improvements
	- Support selecting category node (pick page in template if necessary)
	- Support running Servant without template files (rendering content directly)
- Save cache files for all serialized raw input
- Set scripts and stylesheets in `ServantNode`, bubble them like `externalStylesheets`
	- Actions should output nodestyles and nodestylesheets
- Action-specific user settings
	- JSON (must stay secure on server)? Allow users to declare action-specific configs in site settings?
	- User-facing action names via settings
	-> Private actions (not accessible via HTTP, only other scripts). E.g. database connection
- Better data/storage/working directory services for actions
- Search action (investigate full text search in HTML files)
- Twig parser of `ServantFiles` should pass on treated variables
- Case-insensitive pointers (`ServantNode` and `ServantInput`)?



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
