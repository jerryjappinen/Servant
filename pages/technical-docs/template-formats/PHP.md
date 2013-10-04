
# PHP pages

#### File extension: `.php`

Servant runs on PHP, and **scriptable PHP pages** are supported out of the box. This means that You still write [HTML](), but are also able to use variables and create dynamic sections like *if-else* statements. You will write these in standard PHP.

## Basic example

##### pages/some-category/some-page.php

	<h1>Welcome!</h1>

	<p>Hello! It is the year <?= date('Y') ?>!</p>



## Example using the `$servant` object

##### pages/some-category/some-page.php

	<h1><?= $servant()->page()->name() ?></h1>

	<h3>Other available sites</h3>

	<ul>
	<?php
		foreach ($servant->available()->sites() as $id) {
			print '<li>'.$servant->format()->name($id).'</li>';
		}
	?>
	</ul>
