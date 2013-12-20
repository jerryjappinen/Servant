<h2>Dynamic page menu with PHP</h2>
<ul>

<?php foreach ($page->siblings() as $page): ?>
	<li><a href="<?php echo $page->id(); ?>"><?php echo $page->name(); ?></a></li>
<?php endforeach; ?>

</ul>