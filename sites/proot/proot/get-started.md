
# Get started


## How Proot works

Proot is one PHP backend that serves one or more *app packages*. An app package is a directory under `apps/` and everything in it: HTML, CSS and JS files, plus other assets like images.

You can create as many new web apps you like, and whatever Proot's backend does (like crops images or writes to database) is available to any and all apps, *out-of-the-box*.



## Quick setup

Move the contents of the Proot download to the public folder on your server or local development environment. Things should work out-of-the-box, and you should see an app launching when you point your browser to the location you moved Proot to ([http://localhost/path/to/proot/appname/](http://localhost/path/to/proot/appname/)).

To create an app, copy one of the directories under `templates/` to `apps/` (or [create one from skratch](?category=tutorials&id=first-app)) and start building.

#### **See the [full setup walkthrough](?category=tutorials&id=setup-walkthrough) if you feel you need more detailed instructions.**



## Reading these guides

The paths and URLs mentioned in these guides represent the default values.

- `http://localhost/` is used as an example of an absolute URL that can be loaded in a browser. Replace "localhost" with the address of your server.
- We refer to Proot's location on your server as `path/to/proot/` in these guides. Of course, this can be anything, or nothing, depending on where you've put Proot.
- We can also say that dev tools are available at `dev/`, which refers to the directory in Proot's root. The same goes for all paths in this format.
