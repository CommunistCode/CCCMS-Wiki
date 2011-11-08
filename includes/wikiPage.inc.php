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
		
		$wikiContent  = "<table class='wikiContent'>";
		$wikiContent .= "<tr>";
		
		$wikiContent .= "<td class='heading'><h3>".$contentPiece['heading']."</h3>";

		$wikiContent .= "<form method='post' action='wikiPage.php?wikiPageID=".$_GET['wikiPageID']."'>";
		$wikiContent .= "<input type='hidden' value='".$contentPiece['definitionID']."' name='definitionID' />";

		if ($editMode == 1) {
			
			$wikiContent .= "<input class='editSave' type='submit' name='saveContent' value='Save' />";
		
		} else {
			
			$wikiContent .= "<input class='editSave' type='submit' name='editContent' value='Edit' />";

		}

		if ($contentPiece['content'] != "") {

			$wikiContent .= "<input class='history' type='submit' name='viewHistory' value='History' /></td>";

		}

		$wikiContent .= "</tr>";
		$wikiContent .= "<tr><td class='text' colspan=2 >";

		if ($editMode == 1) {

			$wikiContent .= "<input class='textInput' name='newContent' type='text' value='".$contentPiece['content']."' />";

		} else {

			if ($contentPiece['content'] != "") {

				$wikiContent .= $contentPiece['content'];
			
			} else {

				$wikiContent .= "<em>No information added yet!</em>";

			}

		}

		$wikiContent .= "</form>";
		
		$wikiContent .= "</td></tr>";
		
		$wikiContent .= "</table>";
		
		echo($wikiContent);

	}

?>
