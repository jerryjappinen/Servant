<?php
// Include scripts
// FLAG I really shouldn't hardcode the name of read action...
$temp = $servant->paths()->root('domain').$servant->site()->id().'/scripts/';
if ($servant->action()->id() === 'read') {
	$temp .= implode('/', $servant->pages()->current()->tree()).'/';
}
?>

	<script src="<?= $temp ?>"></script>
</body></html>
