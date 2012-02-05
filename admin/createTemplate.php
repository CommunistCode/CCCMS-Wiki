<?php 

	require_once("../../config/config.php");
	require_once($fullPath."/admin/includes/global.inc.php");
	require_once($fullPath."/admin/includes/checkLogin.inc.php");
	require_once("classes/wikiAdminTools.class.php");

	$wikiAdminTools = new wikiAdminTools();
  $pageTools = new pageTools();

  $title = "Admin : Wiki : Create Template";
  $heading = "Create Template";
  $include = "includes/createTemplate.inc.php";

	if (isset($_POST['createTemplate'])) {

		$i = 0;

		foreach($_POST['definition'] as $definition) {

			if ($definition['heading'] != "" AND $definition['dataType'] != "") {
			
				$definitionArray[$i] = $definition;

				$i++;
				
			}

		}

		$wikiAdminTools->createTemplate($_POST['name'],$_POST['description'],$definitionArray);

	}

  require_once($fullPath."/admin/themes/".$pageTools->getTheme("admin")."/templates/corePage.inc.php");

?>
