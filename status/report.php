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
						<tr><th><?php echo $status->hasFails() ? $status->failCount() : 'No' ?> fails</th></tr>
						<?php foreach ($status->fails() as $message): ?>
							<tr><td><?php echo Markdown($message) ?></td></tr>
						<?php endforeach;?>
					</table>

				<?php endif;?>



				<!-- Errors -->
				<?php if ($status->hasErrors()): ?>

					<table class="orange">
						<tr><th><?php echo $status->hasErrors() ? $status->errorCount() : 'No' ?> errors</th></tr>
						<?php foreach ($status->errors() as $message): ?>
							<tr><td><?php echo Markdown($message) ?></td></tr>
						<?php endforeach;?>
					</table>

				<?php endif;?>



				<!-- Warnings -->
				<?php if ($status->hasWarnings()): ?>

					<table class="yellow">
						<tr><th><?php echo $status->hasWarnings() ? $status->warningCount() : 'No' ?> warnings</th></tr>
						<?php foreach ($status->warnings() as $message): ?>
							<tr><td><?php echo Markdown($message) ?></td></tr>
						<?php endforeach;?>
					</table>

				<?php endif;?>



				<!-- Reports -->
				<?php if ($status->hasReports()): ?>

					<table class="green">
						<tr><th><?php echo $status->hasReports() ? $status->reportCount() : 'No' ?> reports</th></tr>
						<?php foreach ($status->reports() as $message): ?>
							<tr><td><?php echo Markdown($message) ?></td></tr>
						<?php endforeach;?>
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


				<!-- <pre class="buffer"><code><?php echo dump($_SERVER) ?></code></pre> -->
				<!-- <iframe src="phpinfo.php"></iframe> -->



			</div>
		</div>

	</body>
</html>