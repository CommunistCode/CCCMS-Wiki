<?php 
  
  require_once("includes/wikiAdminGlobal.inc.php");

  $page->set("title","Manage Categories");
  $page->set("heading","Manage Categories");

  $page->addInclude("includes/manageCategories.inc.php", array("wikiTools"=>$wikiTools));

	if (isset($_POST['addCategory'])) {

		$wikiAdminTools->addCategory($_POST['name'],$_POST['parentCategory']);

	}

	if (isset($_POST['deleteCategory'])) {

		$wikiAdminTools->deleteCategory($_POST['categoryID']);

	}

  $page->render("corePage.inc.php");

?>
