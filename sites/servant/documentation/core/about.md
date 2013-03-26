
# Backend

Servant is written in PHP, and runs on PHP 5.2+.



## How it works

When a request comes in, Servant runs. This is what happens.

1. `index.php` runs.
	- Path settings are loaded from `paths.php`
	- Paths are sanitized
	- Helpers and classes are loaded for the backend
2. Servant main object is born and initialized.
3. Servant main object's execution flow is run.
