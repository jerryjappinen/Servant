
# Setup walkthrough

This is a very detailed guide to the very simple process of installing Proot. You're just moving files from one place to another, and making sure that a few details are in order.



## 1. Access to a web server

Proot runs on a server, either on a web hosting service or locally on your computer. The list of requirements is very short:

- PHP 5.2 or higher
- Apache
- `mod_rewrite` module for Apache (a.k.a. `rewrite_module`)

If you didn't understand any of that, it's fine. **To get everything in one package on your computer**, it's easiest to download and install one of these:

- Windows users: <a href="http://www.wampserver.com/en/#begin-wrapper" target="_blank">WAMP downloads & installation instructions</a>
- Mac OS X users: <a href="http://www.mamp.info/en/index.html" target="_blank">MAMP downloads</a> (you *don't* need MAMP PRO); (<a href="http://documentation.mamp.info/en/mamp" target="_blank">installation instructions</a>)
- Linux users: <a href="http://www.apachefriends.org/en/xampp-linux.html" target="_blank">XAMPP</a>

Choose the one that's meant for your system and install it. You can also just keep using a web hosting service if that's what you're already doing.

#### **Windows users: WAMP notoriously conflicts with Skype. It's best to force quit Skype to avoid problems when working with WAMP.**



## 2. Enable pretty URLs

Proot uses Apache's *rewrite module* to enable pretty URLs. Usually it's available, but you need to make sure it's turned on:

1. Find *Apache's* settings
2. Find the list of available *modules*
3. Find `rewrite module` or `mod_rewrite`, and make sure it's enabled.

#### **WAMP: there's a colored *W* icon on the system tray when WAMP is turned on &ndash; that's where you'll find WAMP's controls.**
#### **MAMP: This module should be enabled by default, and you don't have to do anything (<a href="http://documentation.mamp.info/en/mamp/faq/which-apache-modules-are-included" target="_blank">see F.A.Q.</a>).**



## 3. Move Proot to your server

[Download](https://bitbucket.org/Eiskis/proot/downloads/proot.zip) and unzip Proot. Move this directory to the public directory of your server:

- **WAMP users:** usually `c:\wamp\www\`
- **MAMP users:** this is `/Applications/MAMP/htdocs/` by default. You can change it in MAMP's settings (the *Apache* tab).
- **XAMPP users:** the public directory is shown in Apache's settings.

It's best to create a subfolder here for Proot's files (e.g. `c:\wamp\www\path\to\proot\`). You can choose any path you want, and we refer to it as `path/to/proot` in these guides.

If you are using a web hosting service, you can just move the files via FTP.

**Note:** Proot comes with a `.htaccess` file, but you can only have one per directory. If you already have a `.htaccess` file where you want to have Proot, merge the contents of both files into one file or create a subfolder for Proot.



## 4. Done!

Proot should now work on your server! Here's how to test it:

1. Point your browser your server ([http://localhost/](http://localhost/) locally).
2. Go to the folder that you moved Proot to (e.g. [http://localhost/path/to/proot/](http://localhost/path/to/proot/)).
	- The default app will load: you'll be redirected, and the name of the app will be added to the URL.
3. Load another app by changing app's name in the URL.

There are multiple sample apps available, all under the `apps/` folder.

You're now ready to [create your first Proot app](?category=tutorials&id=your-first-app)

#### **If things aren't working, see [troubleshooting guide](?category=proot&id=troubleshooting).**



## Optional

### Change default app

One Proot installation can run multiple apps (anything under the `apps/` folder). If a user comes to your site but does not specify an app, Proot will redirect the visitor to one of the apps.

To choose what app is used, open the file `.htaccess` in a text editor (it's a hidden file in the root of the Proot directory). Among other things, you'll find the following lines:

#### `.htaccess`
	# Forward to default app
	RewriteRule ^$ %{REQUEST_URI}proot/ [R=301,L]

Here the default app is `proot`. Change it to whatever else you want on the second line:

	RewriteRule ^$ %{REQUEST_URI}yourapp/



### Enable database support

Databases are **optional**. If you have access to a database credentials, you can enable database support in Proot like this:

1. Insert correct database credentials in `backend/hacks/after_settings.php`.
2. Create a database named `proot_app_myappname` for each app that uses database records.
	- WAMP/MAMP/XAMPP come with phpMyAdmin, which makes this very easy.

You can use now database actions in your apps, if you want to.



### Customize settings

You can [go through system settings](?category=backend&id=settings) if you want.
