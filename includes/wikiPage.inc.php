<?php

	$wikiPage = new wikiPage($_GET['wikiPageID']);

	$wikiPageArray = $wikiPage->getWikiPageArray();

	$wikiPage = "<table>";

	foreach ($wikiPageArray as $contentPiece) {

		$wikiPage .= "<tr>";
		$wikiPage .= "<th>".$contentPiece['heading']."</th>";
		$wikiPage .= "<td>".$contentPiece['content']."</td>";
		$wikiPage .= "</tr>";

	}

	$wikiPage .= "</table>";

	echo($wikiPage);

?>
