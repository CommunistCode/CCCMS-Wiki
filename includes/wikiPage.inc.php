<?php

	$wikiPageArray = $wikiPage->getWikiPageArray();

	foreach ($wikiPageArray as $contentPiece) {

		$editMode = 0;
	
		if ((isset($_POST['definitionID'])) && ($_POST['definitionID'] == $contentPiece['definitionID'])) {

			if (isset($_POST['editContent'])) {

				$editMode = 1;
			
			} else if (isset($_POST['saveContent'])) {

				if (isset($_FILES['newImage'])) {

					if (isset($_POST['imageCaption'])) {

						require_once($fullPath."/helperClasses/imageJiggle/imageJiggle.class.php");

						$status = $wikiTools->insertImage($wikiPage->getID(),
																							$_POST['definitionID'],
																							$_FILES['newImage']['tmp_name'],
																							$_POST['imageCaption']);

						//header("Location: wikiPage.php?wikiPageID=".$wikiPageID."");
						//exit;

					}

				} else if (strcmp($_POST['newContent'],$contentPiece['content']) != 0) {

					$wikiTools->insertContent($wikiPage->getID(),
																		$wikiPage->getTemplateID(),
																		$_POST['definitionID'],
																		$_POST['newContent']);
				
					$contentPiece['content'] = $_POST['newContent'];

					header("Location: wikiPage.php?wikiPageID=".$wikiPageID."");
					exit;

				}

			}
				
		}
		
		$wikiContent  = "<div class='wikiContent'>";
		
		$wikiContent .= "<div class='heading'><h3>".$contentPiece['heading']."</h3>";

		$wikiContent .= "<form method='post' action='wikiPage.php?wikiPageID=".$wikiPageID."' enctype='multipart/form-data'>";
		$wikiContent .= "<input type='hidden' value='".$contentPiece['definitionID']."' name='definitionID' />";

		if ($editMode == 1) {
			
			$wikiContent .= "<input class='cancelButton highlightHover' type='submit' name='cancelEdit' value='x' />";
			$wikiContent .= "<input class='editSave highlightHover' type='submit' name='saveContent' value='Save' />";
		
		} else {
			
			if ($contentPiece['dataType'] == "image") {
				
			$wikiContent .= "<input class='uploadButton highlightHover' type='submit' name='editContent' value='Upload Image' />";
			
			} else {
				
				$wikiContent .= "<input class='editSave highlightHover' type='submit' name='editContent' value='Edit' />";

			}

		}

		if ($contentPiece['content'] != "") {

			$wikiContent .= "<input class='history highlightHover' type='submit' name='viewHistory' value='History' />";

		}

		$wikiContent .= "</div>";
		$wikiContent .= "<div class='text'>";

		if ($editMode == 1) {

			$wikiContent .= $wikiTools->generateInput($contentPiece['dataType'], $contentPiece['content']);
			
		} else {

			if ($contentPiece['content'] != "") {

				$wikiContent .= $pageTools->matchTags($contentPiece['content']);
			
			} else if ($contentPiece['dataType'] == "image") {

				if ($imageArray = $wikiPage->getImages($contentPiece['definitionID'])) {

					if (getType($imageArray) == "array") {

						foreach ($imageArray as $image) {

							$wikiContent .= "<div class='imageThumbContainer'><a href='viewImage.php?imageID=".$image['imageID']."'><img class='wikiImage' src='wikiUserImages/thumbs/".$image['imageID'].".jpg' /></a></div>";

						}

						$wikiContent .= "<div class='imageThumbClear'></div>";

					} else {

						$wikiContent .= "<em>".$imageArray."</em>";

					}

				}

			} else {

				$wikiContent .= "<em>No information added yet!</em>";

			}

		}

		$wikiContent .= "</form>";
		
		$wikiContent .= "</div>";
		$wikiContent .= "</div>";
		
		echo($wikiContent);

	}

?>
