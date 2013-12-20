
# Paths

### Main paths

Servant core looks for specific files based on how they're defined in `backend/paths.php`. You can move files/directories around if you wish: just make sure your changes are reflected in this file.



### Using paths internally

Servant can internally handle arbitrary paths in different formats. A path can be requested in any format from getter methods, or converted with...

	$servant->paths()->format($mypath, $myFormat)

- `'plain'`
	- Relative to Servant's root
	- This is always the default and the format paths are stored in
	- e.g. `site/assets/jquery/`
- `'domain'`
	- Relative to domain root
	- e.g. `mysite/www/site/assets/jquery/`
- `'url'`
	- Absolute URL with host included
	- e.g. `http://www.foo.com/mysite/site/assets/jquery/`
- `'server'`
	- Relative to document root
	- e.g. `Users/username/Documents/htdocs/mysite/site/assets/jquery/`
