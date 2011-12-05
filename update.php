<?php

	require_once("../config/config.php");
	require_once("classes/update.class.php");

	$updateWiki = new updateWiki();

	$updateWiki->update();

?>
