<h2>Dynamic page menu with PHP</h2>
<ul>

<?php foreach ($servant->pages()->current()->level() as $page): ?>
	<li><a href="#"><?= $page()->title() ?> (<?= $page->id() ?>)</a></li>
<?php endforeach; ?>

</ul>