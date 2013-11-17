
# PHP pages

#### File extension: `.php`

Servant runs on PHP, and **scriptable PHP pages** are supported out of the box. This means that You still write [HTML](), but are also able to use variables and create dynamic sections like *if-else* statements. You will write these in standard PHP.

## Basic example

##### site/content/some-category/some-page.php

	<h1>Welcome!</h1>

	<p>Hello! It is the year <?= date('Y') ?>!</p>



## Example using the `$servant` object

##### site/content/some-category/some-page.php

	<h1><?= $page->name() ?></h1>

	<h3>Other available pages</h3>

	<ul>
	<?php
		foreach ($servant->sitemap()->pages() as $page) {
			print '<li>'.$page->name().'</li>';
		}
	?>
	</ul>
