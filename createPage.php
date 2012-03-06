<?php

  require_once("includes/wikiGlobal.inc.php");
  require_once(FULL_PATH."/".MEMBER_MODULE_DIR."/includes/checkLogin.inc.php");

  $page->set("title","Create New Page");
  $page->set("heading","Create New Page");

  $page->addInclude("includes/createPage.inc.php",
                    array("disableWikiCategoryBar"=>"true"));

  $page->render("corePage.inc.php");

?>

<script type="text/javascript" src="scripts/wikiCreatePage.js"></script>

