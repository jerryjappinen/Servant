<?php
error_reporting(E_ALL|E_STRICT);
ini_set('display_errors', '1');
ini_set('error_log', 'statuserrors.log');
date_default_timezone_set('UTC');
header('Content-Type: text/html; charset=utf-8');



/**
* Welcome
*
* This script collects and visualizes general information about this particular Servant installation and its status.
*/

// Dependencies
require_once 'include/baseline.php';
require_once 'include/markdown.php';
require_once 'include/status.php';

// Validation process
$status = new Status();
$status->init('../backend/')->checkCorePaths();
if ($status->noFails()) {
	$status->checkConstants()->checkActions();
}

// Report page
require_once 'include/report.php';
?>