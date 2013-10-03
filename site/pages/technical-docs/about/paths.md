
# Paths

In Servant, paths are stored in a consistent format and always handled in the same way. This makes things easier and all stored path information more versatile.



## Path formats

Servant can handle arbitrary paths in three different formats. A path can be converted from one format to another with `format()->path()`, or requested in any format when using any getter method of a path-related property.

- **Plain**: relative to Servant's root
	- e.g. `site/theme/assets/scripts/jquery/`
	- This is always the default
	- This is the format paths are stored in
- **Domain**: relative to domain root
	- e.g. `mysite/www/site/theme/assets/scripts/jquery/`
- **URL**: absolute URL with host included
	- e.g. `http://www.foo.com/mysite/site/theme/assets/scripts/jquery/`
- **Server**: relative to document root
	- e.g. `Users/jjappinen/Documents/htdocs/mysite/www/site/theme/assets/scripts/jquery/`
