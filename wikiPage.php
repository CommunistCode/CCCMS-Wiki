<?php

	require_once("../config/config.php");
	require_once($fullPath."/classes/pageTools.class.php");
	require_once($fullPath."/wiki/classes/wikiTools.class.php");
	require_once($fullPath."/wiki/classes/wikiPage.class.php");
	require_once($fullPath."/includes/global.inc.php");

	if (isset($_POST['editContent']) OR isset($_POST['saveContent']) OR isset($_POST['viewHistory'])) {

		require_once($fullPath."/membership/classes/member.class.php");
		require_once($fullPath."/membership/classes/memberTools.class.php");
		require_once($fullPath."/membership/includes/checkLogin.inc.php");

	}

	$wikiTools = new wikiTools();

	if (isset($_GET['wikiPageID'])) {

		$wikiPageID = $_GET['wikiPageID'];

	} else if ($_POST['doCreatePage']) {

		$wikiPageID = $wikiTools->createPage($_POST['pageTitle'],$_POST['pageTemplate'],$_POST['pageCategory']);

	} else {
		
		$wikiPageID = 0;

	}

	$wikiPage = new wikiPage($wikiPageID);

	$heading = $wikiPage->getTitle();
	
	if (!isset($_POST['viewHistory'])) {

		$pageEditButton = true;
		$include = "includes/wikiPage.inc.php";

	} else {

		$include = "includes/viewHistory.inc.php";

	}
	
	require_once($fullPath."/wiki/themes/".$pageTools->getTheme("wiki")."/templates/template.inc.php");

?>
