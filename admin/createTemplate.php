<?php 

  require_once("includes/wikiAdminGlobal.inc.php");

  $page->set("title", "Create Template");
  $page->set("heading", "Create Template");

  $page->addInclude("includes/createTemplate.inc.php");

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

  $page->render("corePage.inc.php");

?>
