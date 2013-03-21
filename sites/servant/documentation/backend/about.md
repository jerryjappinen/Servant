
# Class structure



Servant's code is divided in roughly three areas.

1. **Preparation:** `index.php` takes in user's request and prepares to run the program. `paths.php` will be included.
2. **Main Servant:** the main program as a single object. Has the `execute` method that is called by `index.php`.
3. **Components:** Servant's functionality is divided into bite-sized components that handle things like article selection logic, path conversions and HTTP headers.



## ServantObject

All classes in Servant (`ServantMain` and all components) extend `ServantObject`, which includes a set of generic functionality and provides a convention to writing classes for Servant.
