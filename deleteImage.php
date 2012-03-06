<?php

	require_once("includes/wikiGlobal.inc.php");
  require_once(FULL_PATH."/".MEMBER_MODULE_DIR."/classes/member.class.php");

  $page->set("title","Delete Image");
  $page->set("heading","Delete Image");

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

  $page->addContent($content);
  $page->set("disableWikiCategoryBar","true");
  $page->render("corePage.inc.php");

?>
