<h1>LESS</h1>

<?php $less = '
	@color: blue;
	.blah {
		color: @color;
	}
	.foo {
		.bar {
			width: @color;
		}
		.blah;
	}
' ?>

<h3>Original</h3>
<?= html_dump($less) ?>

<h3>Parsed</h3>
<?= html_dump($servant->parse()->lessToCss($less)) ?>
