
# PHP pages

#### File extension: `.php`

Servant runs on PHP, and **scriptable PHP pages** are supported out of the box. This means that You still write [HTML](), but are also able to use variables and create dynamic sections like *if-else* statements. You will write these in standard PHP.

## Basic example

##### sites/sitename/category/article.php

	<h1>Welcome!</h1>

	<?php
		print '<p>Hello! It's the year '.date('Y').'!</p>';
	?>



## Example using the `$servant` object

##### sites/sitename/category/article.php

	<h1><?= $servant()->article()->name() ?></h1>

	<h3>Other available sites</h3>

	<ul>
	<?php
		foreach ($servant->available()->sites() as $id) {
			print '<li>'.$servant->format()->name($id).'</li>';
		}
	?>
	</ul>
