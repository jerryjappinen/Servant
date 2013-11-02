<!DOCTYPE html>
<html lang="en">
	<head>

		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<style type="text/css">
			@-ms-viewport{width: device-width;}
			@-o-viewport{width: device-width;}
			@viewport{width: device-width;}
		</style>

		<title>Servant status</title>

		<!-- Styles -->
		<link rel="stylesheet" href="layers.css" media="screen">
		<link rel="stylesheet" href="status.css" media="screen">

	</head>

<?php
$classes = array();
foreach (array('fails', 'errors', 'warnings', 'reports') as $key) {
	$countMethod = substr($key, 0, -1).'Count';
	$emptyMethod = 'no'.ucfirst($key);
	$classes[] = $status->$emptyMethod() ? 'no-'.$key : $key;
	// $classes[] = $key.'-'.$status->$countMethod();
}


?>

	<body class="<?php echo implode(' ', $classes) ?>">

		<div class="row">
			<div class="row-content buffer">

				<h1>Servant status</h1>

				<!-- Fails -->
				<?php if ($status->hasFails()): ?>

					<table class="red">
						<tr><th colspan="2"><?php echo 'This status board can\'t tell much about the system because of these '.$status->failCount().'things.' ?></th></tr>
						<?php $i = 1 ?>
						<?php foreach ($status->fails() as $message): ?>
							<tr><td><?php echo Markdown($message) ?></td><td class="small discreet right"><?php echo $i ?></td></tr>
							<?php $i++; ?>
						<?php endforeach; unset($i); ?>
					</table>

				<?php endif;?>



				<!-- Errors -->
				<?php if ($status->hasErrors()): ?>

					<table class="orange">
						<tr><th colspan="2"><?php echo 'You should really fix these '.$status->errorCount().' things.' ?></th></tr>
						<?php $i = 1 ?>
						<?php foreach ($status->errors() as $message): ?>
							<tr><td><?php echo Markdown($message) ?></td><td class="small discreet right"><?php echo $i ?></td></tr>
							<?php $i++; ?>
						<?php endforeach; unset($i); ?>
					</table>

				<?php endif;?>



				<!-- Warnings -->
				<?php if ($status->hasWarnings()): ?>

					<table class="yellow">
						<tr><th colspan="2"><?php echo 'These '.$status->warningCount().' things are something you need to take a look at.' ?></th></tr>
						<?php $i = 1 ?>
						<?php foreach ($status->warnings() as $message): ?>
							<tr><td><?php echo Markdown($message) ?></td><td class="small discreet right"><?php echo $i ?></td></tr>
							<?php $i++; ?>
						<?php endforeach; unset($i); ?>
					</table>

				<?php endif;?>



				<!-- Reports -->
				<?php if ($status->hasReports()): ?>

					<table class="green">
						<tr><th colspan="2">Everything seems to be in order.</th></tr>
						<?php $i = 1 ?>
						<?php foreach ($status->reports() as $message): ?>
							<tr><td><?php echo Markdown($message) ?></td><td class="small discreet right"><?php echo $i ?></td></tr>
							<?php $i++; ?>
						<?php endforeach; unset($i); ?>
					</table>

				<?php endif;?>



				<h2>Environment</h2>

				<table class="clean">

					<tr>
						<th>PHP version</th>
						<td>PHP <?php echo phpversion() ?></td>
					</tr>

					<tr>
						<th>Server software</th>
						<td><?php echo $_SERVER['SERVER_SOFTWARE'] ?></td>
					</tr>

					<tr>
						<th>Server protocol</th>
						<td><?php echo $_SERVER['SERVER_PROTOCOL'] ?></td>
					</tr>

					<tr>
						<th>CGI specification</th>
						<td><?php echo $_SERVER['GATEWAY_INTERFACE'] ?></td>
					</tr>

					<tr>
						<th>IP address</th>
						<td><?php echo $_SERVER['SERVER_ADDR'] ?></td>
					</tr>

					<tr>
						<th>Server name</th>
						<td><?php echo $_SERVER['SERVER_NAME'] ?></td>
					</tr>

					<tr>
						<th>Server port</th>
						<td><?php echo $_SERVER['SERVER_PORT'] ?></td>
					</tr>

					<tr>
						<th>Document root</th>
						<td><?php echo $_SERVER['DOCUMENT_ROOT'] ?></td>
					</tr>

				</table>



				<h2>Constants</h2>

				<?php
				$files = rglob_files('../backend/includes/constants/', 'json');
				$jsons = array();
				foreach ($files as $file) {
					$jsons[] = suffix(prefix(trim(file_get_contents($file)), '{'), '}');
				}
				?>
				<pre class="buffer"><code><?php echo implode(','."\n", $jsons) ?></code></pre>



				<!-- <h2>PHP info</h2><iframe src="phpinfo.php"></iframe> -->



			</div>
		</div>

	</body>
</html>