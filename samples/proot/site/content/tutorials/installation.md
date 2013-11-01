
## 1. Access to a server

Proot runs on a web hosting service or a local development environment. The list of requirements is very short:

- PHP 5.2 or higher
- Apache
- `mod_rewrite` module for Apache (a.k.a. `rewrite_module`)

If you didn't understand any of that, it's fine. **To get everything in one package**, it's easiest to download and install one of these:

- Windows users: <a href="http://www.wampserver.com/en/#begin-wrapper" target="_blank">WAMP</a>
- Mac OS X users: <a href="http://www.mamp.info/en/index.html" target="_blank">MAMP</a> (you *don't* need MAMP PRO)
- Linux users: <a href="http://www.apachefriends.org/en/xampp-linux.html" target="_blank">XAMPP</a>

Choose the one that's meant for your system and install it following the installation instructions.



## 2. Enable pretty URLs

Proot uses Apaches *rewrite module* for pretty URLs. Usually it's available, but you need to make sure it's turned on:

1. Find *Apache's* settings
2. Find the list of available *modules*
3. Find `rewrite module` or `mod_rewrite`, and make sure it's enabled.

#### **WAMP users: WAMP notoriously conflicts with Skype. If you are having problems and have Skype on, force quit Skype and try restarting your server.**



## 3. Move Proot to your server

[Download](https://bitbucket.org/Eiskis/proot/downloads/proot.zip) and unzip Proot. Move its contents to the public directory of your server:

- **WAMP users:** usually `c:\wamp\www\`. It's best to create a subfolder here for Proot's files.
- **MAMP users:** this is `/Applications/MAMP/htdocs/` by default. You can change it in MAMP's settings (the *Apache* tab).
- **XAMP users:** the public directory is shown in Apache's settings.

If you are using a web hosting service, you can just move the files via FTP.

**Note:** Proot comes with a `.htaccess` file, but you can only have one per directory. If you already have a `.htaccess` file where you want to have Proot, merge the contents of both files into one file or create a subfolder for Proot.



## 4. Done!

Proot should now work on your server!

To test it, point your browser the server ([http://localhost/](http://localhost/)). Go to the directory that you moved Proot to ([http://localhost/path/to/proot/](http://localhost/path/to/proot/)). The default app (*proot*) will load.

There are multiple sample apps available (all under the `apps/` folder). Load any of them by just adding its name to the URL ([http://localhost/path/to/proot/appname/](http://localhost/path/to/proot/appname/)).

You can still quickly [go through system settings](?category=backend&id=settings). After that, you're ready to [create your first Proot app](?category=tutorials&id=first-app).

#### **If things aren't working, see [troubleshooting guide](?category=proot&id=troubleshooting).**



## Enabling database support (optional)

Databases are **optional**. To enable database support in Proot:

1. Insert correct database credentials in `backend/hacks/after_settings.php`.
2. Create a database named `proot_app_myappname` for each app that uses database records.
	- WAMP/MAMP/XAMPP come with phpMyAdmin, which makes this very easy.



## Changing default app (optional)

Open `.htaccess` (it's a hidden file in the root of the Proot directory) in a text editor and find the following declaration:

#### `.htaccess`
	# Forward to default app
	RewriteRule ^$ %{REQUEST_URI}proot/ [R=301,L]			

`proot` is the default app here. Change it to whatever else you want and you're done.
