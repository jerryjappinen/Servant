
# Installing Servant

Here's the short version: [Download servant.zip](https://bitbucket.org/Eiskis/servant/get/default.zip), unzip it and move it on your server. Usually everything works out-of-the-box, and you will see the default site when you point your browser to servant.

For more details, read on.

[1](templates-and-themes)



## Walkthrough

### 1. Access a server

You need *PHP 5.2+* and *Apache*. Apache needs to have *rewrite_module* enabled (sometimes called *mod_rewrite*). If you have installed [WAMP](http://www.wampserver.com/en/) on Windows as a local development environment, you need to manually enable it in the settings.



### 2. Download Servant

[Download Servant](https://bitbucket.org/Eiskis/servant/get/default.zip). It will come in the form of a `.zip` file you need to extract and move to your server.


## Troubleshooting

#### I don't know where to copy Servant's files

Your server usually has a directory called *Document Root* somewhere in the file system (often with the folder name `www`). It's exact location can vary, but here are some common places to look for.

- If you are using [WAMP](http://www.wampserver.com/en/) on Windows, the default is `C:\wamp\www\`.
- If you are using [MAMP](http://www.mamp.info/en/index.html) on Mac OS X, the default is `/Applications/MAMP/htdocs/`. This can be changed in MAMP's settings, and you can check there to make sure what it is on your specific system.
