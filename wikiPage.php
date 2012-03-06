<?php

	require_once("includes/wikiGlobal.inc.php");

	if (isset($_POST['editContent']) OR isset($_POST['saveContent']) OR isset($_POST['viewHistory']) OR isset($_POST['doCreatePage'])) {

		require_once(FULL_PATH."/".MEMBER_MODULE_DIR."/classes/member.class.php");
		require_once(FULL_PATH."/".MEMBER_MODULE_DIR."/classes/memberTools.class.php");
		require_once(FULL_PATH."/".MEMBER_MODULE_DIR."/includes/checkLogin.inc.php");

	}

	if (isset($_GET['wikiPageID'])) {

		$wikiPageID = $_GET['wikiPageID'];

	} else if ($_POST['doCreatePage']) {

		$wikiPageID = $wikiTools->createPage($_POST['pageTitle'],$_POST['pageTemplate'],$_POST['pageCategory']);

	} else {
		
		$wikiPageID = 0;

	}

  define('WIKI_PAGE_ID',$wikiPageID);

	$wikiPage = new wikiPage($wikiPageID);

	$heading = $wikiPage->getTitle();
	
	if (!isset($_POST['viewHistory'])) {

		$pageEditButton = true;
		$include = "includes/wikiPage.inc.php";

	} else {

		$include = "includes/viewHistory.inc.php";

	}

  $page->set("title",$heading);
  $page->set("heading",$heading);
  
  $page->addInclude($include,
                    array(
                      "wikiPage"=>$wikiPage,
                      "wikiTools"=>$wikiTools,
                      "pageTools"=>$pageTools));

  $page->render("corePage.inc.php");

?>
