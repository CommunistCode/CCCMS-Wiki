<?php

	$wikiPageArray = $wikiPage->getWikiPageArray();
	
	$heading = "Wiki Page - ".$wikiPage->getTitle();

	foreach ($wikiPageArray as $contentPiece) {

		$wikiContent  = "<table class='wikiContent'>";
		$wikiContent .= "<tr>";
		
		$wikiContent .= "<td class='heading'><h3>".$contentPiece['heading']."</h3>";

		$wikiContent .= "<form method='post' action='wikiPage.php?wikiPageID=".$_GET['wikiPageID']."'>";
		$wikiContent .= "<input type='submit' value='Edit'></td>";
		$wikiContent .= "</form>";

		$wikiContent .= "</tr>";
		$wikiContent .= "<tr><td class='text' colspan=2 >".$contentPiece['content']."</td></tr>";
		$wikiContent .= "</table>";
		
		echo($wikiContent);

	}

?>
