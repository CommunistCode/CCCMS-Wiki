<?php

	require_once("../config/config.php");
	require_once($fullPath."/classes/pageTools.class.php");
	require_once($fullPath."/wiki/classes/wikiTools.class.php");
	require_once($fullPath."/includes/global.inc.php");

	$wikiTools = new wikiTools();

	$pageTitle = "View Image";
	$heading = "View Image";
	$include = "includes/viewImage.inc.php";

	$disableWikiCategoryBar = true;

	require_once($fullPath."/wiki/themes/".$pageTools->getTheme("wiki")."/templates/template.inc.php");

?>
