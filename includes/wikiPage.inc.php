<?php

	$wikiPageArray = $wikiPage->getWikiPageArray();

	foreach ($wikiPageArray as $contentPiece) {

		$editMode = 0;
	
		if (isset($_POST['definitionID'])) {

			if ($_POST['definitionID'] == $contentPiece['definitionID']) {

				if (isset($_POST['editContent'])) {

					$editMode = 1;
			
				} else if (isset($_POST['saveContent'])) {

					if (strcmp($_POST['newContent'],$contentPiece['content']) != 0) {

						$wikiTools->insertContent($wikiPage->getID(),
																			$wikiPage->getTemplateID(),
																			$_POST['definitionID'],
																			$_POST['newContent']);
				
						$contentPiece['content'] = $_POST['newContent'];

					}

				}
				
			}

		}
		
		$wikiContent  = "<div class='wikiContent'>";
		
		$wikiContent .= "<div class='heading'><h3>".$contentPiece['heading']."</h3>";

		$wikiContent .= "<form method='post' action='wikiPage.php?wikiPageID=".$wikiPageID."'>";
		$wikiContent .= "<input type='hidden' value='".$contentPiece['definitionID']."' name='definitionID' />";

		if ($editMode == 1) {
			
			$wikiContent .= "<input class='cancelButton highlightHover' type='submit' name='cancelEdit' value='x' />";
			$wikiContent .= "<input class='editSave highlightHover' type='submit' name='saveContent' value='Save' />";
		
		} else {
			
			$wikiContent .= "<input class='editSave highlightHover' type='submit' name='editContent' value='Edit' />";

		}

		if ($contentPiece['content'] != "") {

			$wikiContent .= "<input class='history highlightHover' type='submit' name='viewHistory' value='History' />";

		}

		$wikiContent .= "</div>";
		$wikiContent .= "<div class='text'>";

		if ($editMode == 1) {

			if ($contentPiece['dataType'] == "textarea") {

				$wikiContent .= "<textarea rows='4' class='textInput' name='newContent' >".$contentPiece['content']."</textarea>";

			} else {
			
				$wikiContent .= "<input class='textInput' name='newContent' type='".$contentPiece['dataType']."' value='".$contentPiece['content']."' />";

			}

		} else {

			if ($contentPiece['content'] != "") {

				$wikiContent .= $contentPiece['content'];
			
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
