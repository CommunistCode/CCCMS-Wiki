<?php
  
  $wikiTools = new wikiTools();

  if (defined('WIKI_PAGE_ID')) {

    $wikiPage = new wikiPage(WIKI_PAGE_ID);

  }

	// Find out if and how the category ID is set
	if (isset($_GET['categoryID'])) {

		$categoryID = $_GET['categoryID'];

	} else if (isset($_GET['wikiPageID'])) {

		$categoryArray = $wikiPage->getCategoryArray();
    $categoryID = $categoryArray[0];

	} else {

		$categoryID = 0;

	}

	$topDiv = "linkHeaderTop";

	// Get the sub category information
	$subCategoryArray = $wikiTools->getCategoryList($categoryID);

	// Hide parent category link if there is no parent category
	if ($categoryID != 0) {
		
		echo("<div class='linkHeader ".$topDiv."'>Category Tree</div>");
		$topDiv = "";

		//Make category tree here
		$sortedCategoryTree = $wikiTools->getCategoryPath($categoryID);

		$currentCategory = false;

		echo("<ul>");

		echo("<li><a href='index.php'>Home</a></li>");

		foreach($sortedCategoryTree as $tree) {

			echo("<li>");

			if (count($sortedCategoryTree) == $tree['level']) {

				echo("<strong>");
				$currentCategory = true;

			}

			echo(str_repeat("-",$tree['level'])." <a href='index.php?categoryID=".$tree['id']."'>".$tree['name']."</a> ");
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
			
		echo("<div class='linkHeader ".$topDiv."'>".$categoryHeading."</div>");
		$topDiv = "";
	
		echo("<ul>");

		foreach ($subCategoryArray as $category) {

			echo("<li><a href='index.php?categoryID=".$category['wikiCategoryID']."'>".$category['name']."</a> ");
			echo("(".$wikiTools->countPagesInCategoryRecurring($category['wikiCategoryID']).")</span>");

		}

		echo("</ul>");

	}

	$popularPageArray = $wikiTools->getCategoryPages($categoryID, 10);

	if (isset($popularPageArray)) {

		echo("<div class='linkHeader'>Popular Pages</div>");

		echo("<ul>");

		foreach ($popularPageArray as $popularPage) {

			$popularPageTitle = $popularPage['title'];
			$popularPageTitleOrig = $popularPage['title'];

			if (strlen($popularPageTitle) > 13) {

				$popularPageTitle = substr($popularPageTitle,0,13)."&hellip;";

			}

			echo("<li><a href='wikiPage.php?wikiPageID=".$popularPage['wikiPageID']."&categoryID=".$categoryID."' title='".$popularPageTitleOrig."'>".$popularPageTitle."</a></li>");

		}

		echo("</ul>");

	}

?>

<div class='linkHeader'>Actions</div>

<ul>
	<li><a href='createPage.php'>Create New Page</a></li>
</ul>

