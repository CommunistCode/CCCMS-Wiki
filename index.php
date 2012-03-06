<?php

	require_once("includes/wikiGlobal.inc.php");

  $page->set("title","Wiki");
  $page->set("heading","Wiki");

  $disableWikiCategoryBar = false;
	
  if (!isset($_GET['categoryID']) || $_GET['categoryID'] == 0) {

		$disableWikiCategoryBar = true;

	}

  $page->addInclude("includes/showCategoryPages.inc.php",
                    array(
                      "wikiTools"=>$wikiTools,
                      "pageTools"=>$pageTools,
                    ));

  $page->set("disableWikiCategoryBar",$disableWikiCategoryBar);
  $page->render("corePage.inc.php");

?>
