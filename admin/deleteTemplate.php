<?php 

  require_once("includes/wikiAdminGlobal.inc.php");

  $page->set("title", "Delete Template");
  $page->set("heading", "Delete Template");

  $page->addInclude("includes/deleteTemplate.inc.php", array("wikiTools"=>$wikiTools));

	if (isset($_POST['deleteTemplate'])) {

		$wikiAdminTools->deleteTemplate($_POST['templateID']);

	}

  $page->render("corePage.inc.php");

?>
