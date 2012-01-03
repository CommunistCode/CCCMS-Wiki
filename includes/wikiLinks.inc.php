<?php

	// New wiki tools object
	$wikiTools = new wikiTools();

	// Find out if and how the category ID is set
	if (isset($_GET['categoryID'])) {

		$categoryID = $_GET['categoryID'];

	} else if (isset($_GET['wikiPageID'])) {

		$categoryArray = $wikiPage->getCategoryArray();
    $categoryID = $categoryArray[0];

	} else {

		$categoryID = 0;

	}

	$topDiv = "TopDiv";

	// Get the sub category information
	$subCategoryArray = $wikiTools->getCategoryList($categoryID);

	// Hide parent category link if there is no parent category
	if ($categoryID != 0) {
		
		echo("<div class='wikiLinkHeading".$topDiv."'>Category Tree</div>");
		$topDiv = "";

		//Make category tree here
		$sortedCategoryTree = $wikiTools->getCategoryTree($categoryID);

		$i = 0;
		$currentCategory = false;

		echo("<ul>");

		echo("<li><a href='index.php'>Home</a></li>");

		foreach($sortedCategoryTree as $tree) {

			$i++;
			echo("<li>");

			if (count($sortedCategoryTree) == ($i)) {

				echo("<strong>");
				$currentCategory = true;

			}

			echo(str_repeat("-",$i)." <a href='index.php?categoryID=".$tree['id']."'>".$tree['name']."</a> ");
			echo("(".$wikiTools->countPagesInCategoryRecurring($tree['id']).")");

			if ($currentCategory) {

				echo ("</strong>");

			}

			echo("</li>");

		}

	}

	// Check if there are any sub-categories returned
	if (isset($subCategoryArray)) {

		if ($categoryID != 0) {
			
			$categoryHeading = "Sub-Categories";

		} else {

			$categoryHeading = "Categories";

		}
			
		echo("<div class='wikiLinkHeading".$topDiv."'>".$categoryHeading."</div>");
		$topDiv = "";
	
		echo("<ul>");

		foreach ($subCategoryArray as $category) {

			echo("<li><a href='index.php?categoryID=".$category['wikiCategoryID']."'>".$category['name']."</a> ");
			echo("(".$wikiTools->countPagesInCategoryRecurring($category['wikiCategoryID']).")</li>");

		}

		echo("</ul>");

	}

	$popularPageArray = $wikiTools->getCategoryPages($categoryID, 10);

	if (isset($popularPageArray)) {

		echo("<div class='wikiLinkHeading'>Popular Pages</div>");

		echo("<ul>");

		foreach ($popularPageArray as $popularPage) {

			$popularPageTitle = $popularPage['title'];
			$popularPageTitleOrig = $popularPage['title'];

			if (strlen($popularPageTitle) > 15) {

				$popularPageTitle = substr($popularPageTitle,0,15)."&hellip;";

			}

			echo("<li><a href='wikiPage.php?wikiPageID=".$popularPage['wikiPageID']."&categoryID=".$categoryID."' title='".$popularPageTitleOrig."'>".$popularPageTitle."</a></li>");

		}

		echo("</ul>");

	}

?>

<div class='wikiLinkHeading'>Actions</div>

<ul>
	<li><a href='createPage.php'>Create New Page</a></li>
</ul>

