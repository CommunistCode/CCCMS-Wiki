<?php

	$wikiTools = new wikiTools();

	if (isset($_GET['categoryID'])) {

		$categoryID = $_GET['categoryID'];

	} else if (isset($_GET['wikiPageID'])) {

		$categoryID = $wikiPage->getCategoryID();

	} else {

		$categoryID = 0;

	}

	$categoryArray = $wikiTools->getCategoryList($categoryID);

	if (isset($categoryArray)) {

		if ($categoryID != 0) {
		
			echo("<a href='index.php?categoryID=".$wikiTools->getCategoryParentID($categoryID)."'>Up a Level</a><br /><br />");

		echo("<strong>".$wikiTools->getCategoryName($categoryID)."</strong><br />");
		} else {

		echo("<strong>Categories</strong><br /><br />");

		}

		foreach ($categoryArray as $category) {

			echo("<a href='index.php?categoryID=".$category['wikiCategoryID']."'>".$category['name']."</a>");
			echo("<br />");

		}

	} else {

		echo("<a href='index.php?categoryID=".$wikiTools->getCategoryParentID($categoryID)."'>Up a Level</a><br /><br />");
		
		echo("End of sub categories");

	}

?>
