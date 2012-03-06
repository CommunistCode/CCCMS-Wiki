<?php

	require_once("includes/wikiGlobal.inc.php");

  $page->set("title","View Image");
  $page->set("heading","View Image");

	$page->addInclude("includes/viewImage.inc.php",array("wikiTools"=>$wikiTools));

	$page->set("disableWikiCategoryBar","true");

  $page->render("corePage.inc.php");

?>
