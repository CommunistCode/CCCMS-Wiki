<?php 

	require_once("../../config/config.php");
	require_once($fullPath."/admin/includes/global.inc.php");
	require_once($fullPath."/admin/includes/checkLogin.inc.php");
	require_once("classes/wikiAdminTools.class.php");

	$wikiAdminTools = new wikiAdminTools();
  $pageTools = new pageTools();

  $title = "Admin : Wiki : Delete Template";
  $heading = "Delete Template";
  $include = "includes/deleteTemplate.inc.php";

	if (isset($_POST['deleteTemplate'])) {

		$wikiAdminTools->deleteTemplate($_POST['templateID']);

	}

  require_once($fullPath."/admin/themes/".$pageTools->getTheme("admin")."/templates/corePage.inc.php");

?>
