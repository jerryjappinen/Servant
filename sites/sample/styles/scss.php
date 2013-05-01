<h1>SCSS</h1>

<?php $scss = '
	$color: blue;
	@mixin blah {
		color: $color;
	}
	.foo {
		.bar {
			width: $color;
		}
		@include blah;
	}
' ?>

<h3>Original</h3>
<?= html_dump($scss) ?>

<h3>Parsed</h3>
<?= html_dump($servant->parse()->scssToCss($scss)) ?>
