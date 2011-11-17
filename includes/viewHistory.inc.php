<?php

	$memberTools = new memberTools();

	$wikiHistoryArray = $wikiTools->getHistory($_GET['wikiPageID'], $_POST['definitionID']);

	echo("<a href='wikiPage.php?wikiPageID=".$_GET['wikiPageID']."'>Back to Wiki Page</a><br /><br />");

	$wikiHistoryTable = "<table class='historyTable'>";

	foreach ($wikiHistoryArray as $historyPiece) {

		$wikiHistoryTable .= "<tr>";
		$wikiHistoryTable .= "<td class='date'>".date("d/m/Y",$historyPiece['date'])."</td>";
		$wikiHistoryTable .= "<td class='member'>".$memberTools->getUsername($historyPiece['memberID'])."</td>";

		$wikiHistoryTable .= "<td class='";

		if ($historyPiece['isCurrent'] == 1) {

			$wikiHistoryTable .= "isCurrent";

		} else {

			$wikiHistoryTable .= "content";

		}
		
		$wikiHistoryTable .= "'>".$historyPiece['content']."</td>";
		$wikiHistoryTable .= "<tr>";

	}

	$wikiHistoryTable .= "</table>";

	echo($wikiHistoryTable);

?>
