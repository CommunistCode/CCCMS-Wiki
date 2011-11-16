<div class='wikiShowCategoryPages'>

	<?php

		$wikiTools = new wikiTools();

		if (isset($_GET['categoryID']) && $_GET['categoryID'] != 0) {

			$categoryID = $_GET['categoryID'];
			$pageArray = $wikiTools->getCategoryPages($categoryID);
	
			if (isset($pageArray)) {

				echo("<h4>Category Pages:</h4>");

				echo("<ul>");

				foreach($pageArray as $page) {

					echo("<li><a href='wikiPage.php?wikiPageID=".$page['wikiPageID']."&categoryID=".$categoryID."'>".$page['title']."</a>");

				}

				echo("</ul>");

			} else {

				echo("<strong>There are currently no pages in this category!</strong>");

			}

		} else {

			$categoryID = 0;
			$pageArray = $pageTools->getDynamicContent($pageTools->getPageIDbyDirectLink("wiki/index.php"));

			echo($pageTools->matchTags($pageArray['text']));

		}

	?>

</div>
