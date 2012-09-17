<?php

  // Require global include
  require_once("../../global/includes/global.inc.php");
  require_once(FULL_PATH."/admin/config/moduleConfig.inc.php");

  // Require adminTools class
  require_once(FULL_PATH."/admin/classes/adminTools.class.php");

  // Require blogTools class
  require_once("../classes/wikiTools.class.php");
  require_once("classes/wikiAdminTools.class.php");

  // Create adminTools class
  $adminTools = new adminTools();
  $wikiTools = new wikiTools();
  $wikiAdminTools = new wikiAdminTools();

  if (!isset($checkLogin) || $checkLogin != false) {

    // Check logged in
    require_once(FULL_PATH."/admin/includes/checkLogin.inc.php");

  }

?>
