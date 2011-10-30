<?php

	echo("<br /><br />");

	$wikiTools = new wikiTools();

	if (isset($_GET['categoryID'])) {

		$categoryID = $_GET['categoryID'];

	} else {

		$categoryID = 0;

	}

	$pageArray = $wikiTools->getCategoryPages($categoryID);

	if (isset($pageArray)) {

		foreach($pageArray as $page) {

			echo("<a href='wikiPage.php?wikiPageID=".$page['wikiPageID']."'>".$page['title']."</a>");

			echo("<br />");

		}

	} else {

		echo("No pages in this category!");

	}

?>
