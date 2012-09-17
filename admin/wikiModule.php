<?php 

  require_once("includes/wikiAdminGlobal.inc.php");

  $page->set("title","Wiki Management");
  $page->set("heading","Wiki Management");
  $page->addContent("Welcome to the wiki module admin area");
  $page->render("corePage.inc.php");

?>

