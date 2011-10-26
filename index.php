<?php

	require_once("../config/config.php");
	require_once($fullPath."/classes/pageTools.class.php");
	require_once($fullPath."/wiki/classes/wikiTools.class.php");
	require_once($fullPath."/includes/global.inc.php");

	$wikiTools = new wikiTools();

	$heading = "Wiki";
	$content = "Welcome to the Wiki"; 

	require_once($fullPath."/wiki/themes/".$pageTools->getTheme("wiki")."/templates/template.inc.php");

?>
