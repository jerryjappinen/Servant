<?php

$output = '
# Templates

Templates are app packages that help you kickstart your app development. Templates usually include common functionality and views that you would otherwise have to add yourself.

There are multiple templates, for different kinds of apps and different kind of workflows. One template might be essentially a complete app, while another might be just a skeleton to help you on your way.

To start developing a Proot app from a template, simply copy one of the templates to the app directory with a new name and start devving.



## Available templates
';



// List directories under templates/
$templates = glob_dir('../templates/');
if (!empty($templates)) {
	foreach ($templates as $value) {
		$temp = json_decode(file_get_contents($value.'/app.json'), true);
		$value = basename($value);

		// Template info
		$output .= "\n\n".'### '.$value;

		if (isset($temp['meta']['description']) and !empty($temp['meta']['description'])) {
			$output .= "\n\n".$temp['meta']['description'];
		}

	}

// No templates
} else {
	$output .= 'I couldn\'t find any templates under <code>'.$p['root'].'templates/'.'</code>.';

}


echo Markdown($output);

?>