
Any other actions that you expect to be available in the system in order for an app that uses your action to function sensibly.

#### `configuration.php`
	$action['dependencies'] = array(
		'behavior',
		'style',
		'template',
		'images' => array('show', 'list')
	);
