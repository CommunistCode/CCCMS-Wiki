<?php

  // Require global include
  require_once("../global/includes/global.inc.php");
  require_once("config/moduleConfig.inc.php");

  // Require wikiTools
  require_once(FULL_PATH."/".MODULE."/classes/wikiTools.class.php");

  // Require wikiPages
  require_once(FULL_PATH."/".MODULE."/classes/wikiPage.class.php");

  // Create memberTools object
  $wikiTools = new wikiTools();

?>
