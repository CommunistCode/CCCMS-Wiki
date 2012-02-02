<?php

	require_once("../config/config.php");
	require_once($fullPath."/classes/pageTools.class.php");
	require_once($fullPath."/wiki/classes/wikiTools.class.php");
	require_once($fullPath."/includes/global.inc.php");
  require_once($fullPath."/membership/classes/member.class.php");

	$wikiTools = new wikiTools();

	$pageTitle = "Wiki : Delete Image";
	$heading = "Delete Image";

  $disableWikiCategoryBar = true;

  if (isset($_SESSION['member'])) {

    $imageDetailsArray = $wikiTools->getImageDetails($_GET['imageID']);

    $member = unserialize($_SESSION['member']);

    if ($member->getID() == $imageDetailsArray['memberID']) {

      $wikiTools->deleteImage($_GET['imageID']);
      
      $content = "Image was deleted!";

    }

  } else {

    $content = "You do not have the permissions to delete this image!";
  
  }

	require_once($fullPath."/wiki/themes/".$pageTools->getTheme("wiki")."/templates/template.inc.php");

?>
