<?php

	require_once(FULL_PATH."/".MEMBER_MODULE_DIR."/classes/memberTools.class.php");
	require_once(FULL_PATH."/".MEMBER_MODULE_DIR."/classes/member.class.php");

	$memberTools = new memberTools();

	$imageDetailsArray = $_wikiTools->getImageDetails($_GET['imageID']);

  $delete = "";
  
  if (isset($_SESSION['member'])) {
    
    $member = unserialize($_SESSION['member']);
    $currentMemberID = $member->getID();

    if ($imageDetailsArray['memberID'] == $currentMemberID) {
  
      $delete = "(<a href='deleteImage.php?imageID=".$_GET['imageID']."'>Delete</a>)";

    }
 
  }

?>

<div class='wikiImageFullContainer'><img class='wikiImageFull' src='wikiUserImages/full/<?php echo($_GET['imageID']); ?>.jpg'></div>

<?php echo("<p class='imageNo'>".$_wikiTools->getWikiTitle($imageDetailsArray['wikiPageID'])." - Image (".($imageDetailsArray['imageIndex']+1)."/".$imageDetailsArray['noImagesInSet'].") - Uploaded by: ".$memberTools->getUsername($imageDetailsArray['memberID'])." ".$delete."</p>"); ?>

<table class='fullImageLinkTable'>
	<tr>

		<td colspan=3 class='imageCaption'>
		
			<?php

				echo($imageDetailsArray['caption']);

			?>
		
		</td>

	</tr>
</table>
<br />
<table class='fullImageLinkTable'>
	<tr>

		<td class='prevLink'>
			<?php 
				
				if (isset($imageDetailsArray['prevImage'])) {
					
					echo ("<a href='viewImage.php?imageID=".$imageDetailsArray['prevImage']."'>Previous (".($imageDetailsArray['imageIndex'])."/".$imageDetailsArray['noImagesInSet'].")</a>");

				}

			?>
		</td>
		
		<td class='centerLink'><a href='wikiPage.php?wikiPageID=<?php echo($imageDetailsArray['wikiPageID']); ?>'>Back to Wiki Page</a></td>
		
		<td class='nextLink'>
			<?php
			
				if (isset($imageDetailsArray['nextImage'])) {
					
					echo("<a href='viewImage.php?imageID=".$imageDetailsArray['nextImage']."'>(".($imageDetailsArray['imageIndex'] + 2)."/".$imageDetailsArray['noImagesInSet'].") Next</a>");

				}

			?>
		</td>

	</tr>
</table>
