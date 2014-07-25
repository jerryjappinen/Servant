
# Troubles?

<big>Oh no! Servant tries to automate as much and intelligently as possible so you'd never have to visit this page. Technology doesn't always work, though, so here's a detailed guide on troubleshooting.</big>

## Setup process

These are detailed instructions on how to set up Servant. You'll have it running in minutes.

### 1. Access a server

Basically, you need *PHP 5.3+* and a server with support for *rewrite_module* (sometimes called *mod_rewrite*). Most servers have this.

**Note!** If you have installed [WAMP](http://www.wampserver.com/en/) on Windows as a local development environment, you need to manually enable this module in the settings.



### 2. Download Servant

[Download Servant](https://github.com/Eiskis/Servant/archive/master.zip). It will come in the form of a `.zip` file that you need to extract and move to your server.



### 3. Point your browser to Servant

Go to your server, where you just moved Servant's files. If Servant's the default site is loading, Servant is now installed correctly.

If this isn't the case, continue to troubleshooting.



## Common issues

#### Apache modules

The default Servant release uses the following modules:

- *rewrite_module*
- *php5_module*
- *deflate_module* (optional)

These should be enabled in Apache.



#### File permissions

Sometimes file or directory permissions aren't copied over as expected. This is what you should have:

- `cache/` and `data/` folders
	- Servant generates, edits, reads and removes directories and files inside these folders.
	- Both should be **writable**.
- All folders
	- Servant reads script files under `backend/`, `templates/` and `pages/`. All of these contain arbitrary subfolders.
	-`backend/`, `templates/` and `pages/`
- The only *PHP* script that is executed directly is `backend/index.php`.
	- Apache with *mod_php*, however, will still read this file so it should be enough to keep this **readable** like everything else.

These apply to both the **web server user** and the **command line user**. These can either be the same or two different users, depending on your system.



#### I don't know where to copy Servant's files

Your server usually has a directory called *Document Root* somewhere in the file system (often with the folder name `www`). Its exact location can vary, but here are some places to look:

- If you are using [WAMP](http://www.wampserver.com/en/) on Windows, the default is `C:\wamp\www\`.
- If you are using [MAMP](http://www.mamp.info/en/index.html) on Mac OS X, the default is `/Applications/MAMP/htdocs/`.
	- This can be changed in MAMP's settings, and you can check there to make sure what it is on your specific system.



#### I'm getting a "Forbidden" error

Make sure you `.htaccess` files have been copied to your server. They forward requests from one place to another, prevent public directory listings and deny access to some files for security reasons. They are hidden files, and as such might sometimes slip through when you're copying files from one place to another.

There's one file in the root folder, and three in other directories:

- `.htaccess` (root directory)
- `backend/.htaccess` (the same place as `index.php`)
- `pages/.htaccess` (pages folder)
- `templates/.htaccess` (template folder)



#### PHP can't find files

Servant needs to know where it's running. Normally it detects this automatically, but on some, more complicated environments this doesn't work. If this is the case:

1. Open `backend/core/paths.php`
2. Uncomment `'document root'`, `'root'` and/or `'host'`
3. Give these the values that correspond to your server

You shouldn't have to, but you can also go through `.htaccess` and make sure nothing there conflicts with your environment's settings.
