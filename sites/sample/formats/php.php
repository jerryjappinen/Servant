<h2>Available sites</h2>
<ul>

<?php foreach ($servant->available()->sites() as $id): ?>
	<li><a href="#"><?= $servant->format()->name($id) ?> (<?= $id ?>)</a></li>
<?php endforeach; ?>

</ul>