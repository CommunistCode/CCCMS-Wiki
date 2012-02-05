<?php 

	require_once("../../config/config.php");
	require_once($fullPath."/admin/includes/global.inc.php");
	require_once($fullPath."/admin/includes/checkLogin.inc.php");
	require_once("classes/wikiAdminTools.class.php");
	require_once($fullPath."/wiki/classes/wikiTools.class.php");

	$wikiAdminTools = new wikiAdminTools();
	$wikiTools = new wikiTools();
  $pageTools = new pageTools();

  $title = "Admin : Wiki : Manage Categories";
  $heading = "Manage Categories";
  $include = "includes/manageCategories.inc.php";

	if (isset($_POST['addCategory'])) {

		$wikiAdminTools->addCategory($_POST['name'],$_POST['parentCategory']);

	}

	if (isset($_POST['deleteCategory'])) {

		$wikiAdminTools->deleteCategory($_POST['categoryID']);

	}

  require_once($fullPath."/admin/themes/".$pageTools->getTheme("admin")."/templates/corePage.inc.php");

?>
