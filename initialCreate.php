<?php

	require_once('../config/config.php');
	require_once("classes/initialCreate.class.php");

	$create = new initialInstallWiki();

	$create->createTables();
	$create->populateTables();

?>
