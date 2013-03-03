
# Shared code

The view lifecycle is what determined what happens during different stages of the lifetime of your application. You will, however, want to share code between views and view methods.

You can add JavaScript files to `shared/` in your app package. These files will be converted to JS and included in your app object. So, anything there is available in any of your view methods, throughout the application (the shorthand `shared` is also available).



## Example

Here, we create global, custom logging functionality once and use it in two separate view methods.

#### `shared/print.js`
	{
		app: function () {
			return p.dev.log(p.app.get());
		}
	}

Namespaces for shared functions are automatically generated based on the directory structure.

#### `views/prepare.js`
	shared.print.app();

#### `views/page/render.js`
	shared.print.app();
