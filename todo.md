
# Servant development

## Backlog

- **Template parsers**
	- Support MtHaml/Twig combo
	- Remove unsupported parsers
- **Initing inputs and actions**
	- Very laborous and weird
	- Action's init behavior is weird and not very handy
	- TODO
		- Make `ServantInput->fetch()` return `null` if validation fails
		- Take in pointer and other input separately
		- Improve documentation
- **`API`/`about`/`json-vars` action**
	- For requesting info with AJAX
	- Setting `json` files scraped from assets, templates, nodes
	- Handles existing and missing `servant` objects in JS
	- Outputs error in console in debug mode
- **Redirect URLs**
	- Include excess pointer parameters in redirect URLs
- **Unit test action**
	- Easy way to get unit tests started, even if requires a not-completely-broken system
	- TODO
		- Write test runner utility
		- Write some unit tests
		- Write action that inits test suites and passes them `$servant`
		- Show test results in JSON
		- Separate HTML outputter
- **`json` settings files**
	- Supported in site assets, templates and nodes
	- JSON file contents outputted as JS hashes
	- Available like scripts and stylesheets
	- `json` reader and parser available globally (now in `ServantManifest` only)
- **Loading assets under `pages/`**
	- Get node's assets in subfolders with no child pages
- **`ServantPath`**
	- Use in all path properties
	- Provide formatting, manipulation and sanitization
		- `__toString`
		- `->plain()`
		- `->domain()`
		- `->url()`
		- `->server()`
		- `->split()`
		- `->foo('more', 'url', 'parameters')`
- **Internal URL scheme**
	- Use pseudo protocols to point to different locations:
		- `servant://` (root)
		- `assets://`
		- `actions://`
		- `pages://`
		- `templates://`
	- In different contexts, one of these serves as the default root
	- Have one PHP method that handles these conversions (so you can write something like `pointer('assets://foo')`)
	- Apply URL parsing to HTML form's action tag
	- Treat `../` as expected when parsing URLs
- **Directory structure**
	- Support multiple locations for templates, actions etc.
		- Defined in `paths.php`
		- Custom templates and actions are easily distinguished from those provided by default
		- Makes updating Servant easier
- **Templates**
	- Support passing category node to stock templates
	- Support running Servant without template files (`ServantTemplate` working without files should render content directly)
- **Cache**
	- Action-specific cache times, respect node-specific cache settings in site action
		- Only site-wide browser and server cache times are supported in `ServantResponse` now
		- ACTIONS can set browser **and** server cache times
		- Node-specific actions respect node-specific cache settings
	- Save cache files for all serialized raw input
- **`ServantNode`**
	- Set scripts and stylesheets in `ServantNode`, bubble them like `externalStylesheets`
		- Actions should output nodestyles and nodestylesheets
- **`ServantData`**
	- Chart out use cases
	- Provide better services for managing files
- **`ServantManifest`**
	- Custom user settings
	- Private actions
		- Not accessible via HTTP
		- Other scripts can use the action as usual
		- Use case: handling database connection via one action which doesn't respond to HTTP
		- Should not be visible in public responses (might be tricky)
	- Action-specific user settings
		- Allow users to declare action-specific config items
- **Search action**
	- Scan available pages, action names etc.
	- Should scan result HTML instead of 
	- Doesn't have to be very fast
	- Needs investigating full text search in HTML
	- JSON search for AJAX, separate HTML outputter
- **Sanitize JSON formatting**
	- Trailing commas in lists
- **Case-insensitive pointers**
	- Support in `ServantNode`, `ServantInput` etc.
	- Leads to case-insensitive URLs
	- Files in a case-sensitive file system might create confusion
- **`html` template**
	- Send pointer parameters to page-specific external scripts/stylesheets that point to local Servant actions?



## Bugs

- Page pointers are weird if there is only one directory under subfolder
- Sometimes `ServantInput` might fail on `Validator` class not being available despite attempting to load it via `ServantUtilities`.
- Twig parser of `ServantFiles` does not pass on treated variables to script files running in queue
- Cache files are sometimes created in debug mode



## Sample projects, tutorials and help

- 2 min of Servant (video)
	- General
		- Everything's automated
		- Sitemap
		- Multiple template formats
			- `HTML`
			- `Markdown`
			- `Textile`
			- `PHP`
			- `Jade`
			- `HAML`
			- `Twig`
		- Asset minification
		- HTML header boilerplate
		- `LESS` and `SCSS` compilers
		- Automatic fancy URLs
			- Augmented by manual redirects
		- Use cases
	- What you see first
	- Pages
	- Assets
	- Settings
	- Templates
	- Actions
- Getting started tutorial site
	- Default content in Servant releases
	- Tutorial pages that provide info on
		- environment variables,
		- paths and locations,
		- how to get started editing a new site,
		- site settings items
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



## Project skeleton generator

- Remove
	- `.git/`
	- `changelog.md`
	- `todo.md`
	- `readme.md`
	- `samples/`
	- `status/`
	- All templates except for `html` and `debug`
- Tutorial content
	- `settings.json`
	- `site` template that nests `html`
	- Two sample pages
		- Intro, tutorial
		- Environment reference
	- Assets
		- `splash.jpg`
		- `icon.png`
		- One `.less` file
		- One `.js` file



## Security

- Using multiple `.htaccess` files is bad
	- Easy to omit when transferring files
	- Hard to keep track of
- `settings.json` should be hidden
- All script files except `backend/index.php` should be hidden



## Add-on status board / admin section

Some kind of status page that indicates if things like paths are in order, what settings are active etc. Helps during installation and warns about common pitfalls.

- Report cards structure
- Test routines separated from main thingy
- Settings file generation + maintenance
- Status board
- Online page editor




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
