<?php 

	require_once("../../config/config.php");
	require_once($fullPath . "/admin/includes/global.inc.php");
	require_once($fullPath . "/admin/includes/checkLogin.inc.php");

  $pageTools = new pageTools();

  $title = "Admin : Wiki Module";
  $heading = "Wiki Module";
  $content = "Welcome to wiki module admin area";

  require_once($fullPath."/admin/themes/".$pageTools->getTheme("admin")."/templates/corePage.inc.php");

?>

