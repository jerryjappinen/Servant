
#### **Always use the developer tools at `dev/` to get more information of your system. Dev tools spot common problems and usually give a better idea on how to fix them.**



## URLs not working

### Make sure requirements are met
Make sure you have `mod_rewrite` or `rewrite_module` enabled in Apache. In rare cases, you might have to uncomment `Options +FollowSymlinks` in `.htaccess`.

### Check PHP's path settings
Proot will attempt to find out where it is running on its own, but sometimes you have to manually define its location in `paths.php`.

- Make sure that the initial value of `$paths['root']` represents the location you moved Proot to. Uncomment the line to override automatic detection.
- `$_SERVER['DOCUMENT_ROOT']` is used, but in some rare cases it doesn't work as intended. You might have to hack the path it gives.

### Check .htaccess

URL rewrites are declared in the `.htaccess` file (it's a hidden file in the root of the Proot directory). You should make sure all the paths are correct and that the settings correspond to what your server wants (lines beginning with `#` are disabled).

#### `.htaccess`

	# Settings

	# Disable directory listings
	Options -Indexes

	# Allow URL rewrites (uncomment the next line if needed on your server)
	# Options +FollowSymLinks
	RewriteEngine on

	# Might be required for CORS (uncomment if needed on your server)
	# Header set Access-Control-Allow-Origin *



	# Custom endpoints

	# Location of index.php from root
	DirectoryIndex backend/index.php

	# Default app
	RewriteRule ^$ /proot/home/ [R=301,L]

	# Access any action of an app
	RewriteRule ^use/?([^/]*)/?([^/]*)/?([^/]*)/?([^/]*)/?([^/]*)/?([^/]*)/?([^/]*)/?([^/]*)/?([^/]*)/? \backend/index.php?app=$1&action_parent=$2&action=$3&input=$4;$5;$6;$7;$8;$9 [PT]

	# Respect natural paths
	RewriteCond %{REQUEST_FILENAME} !-f
	RewriteCond %{REQUEST_FILENAME} !-d

	# Access the web client of an app directly
	RewriteRule ^/?([^/]*)/?([^/]*)/?([^/]*)/?([^/]*)/?([^/]*)/?([^/]*)/?([^/]*)/? \backend/index.php?action_parent=web&action=client&app=$1&input=$2;$3;$4;$5;$6;$7	

### Use Paths and URLs correctly in apps
[Read more about URLs & paths](?category=apps&id=urls)



### Make sure directory names don't clash apps or endpoints
All paths and directory names are configured via variables, and can be changed if really necessary. To change the main directory names used internally, or endpoints used in URLs, edit them in `.htaccess` (located in root directory) and `backend/paths.php` (located in same folder as `index.php`). **Only do this if absolutely necessary!**

[Read more about reserved words](?category=apps&id=urls)



## Cache not working
Make sure there Proot's root directory is writable.

[Read more about caching](?category=backend&id=caching)



## Database not working
Make sure you have inserted correct database credentials in `backend/hacks/after_settings.php`. You also need to manually create a database called `proot_app_appname` for all apps that use database records.
