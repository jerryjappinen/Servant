
# Utilities

You can include generic utilities and libraries to help you in creating actions. Separate utilities might come in handy with things like image manipulation or heavy database operations.

To include any of the utilities that are available in your Proot installation (under `backend/utilities/`), use configuration:

#### `configuration.php`
	$action['utilities'] = array('redbean', 'markdown');
