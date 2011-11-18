<div class='wikiShowCategoryPages'>

	<?php

		$wikiTools = new wikiTools();

		if (isset($_GET['categoryID']) && $_GET['categoryID'] != 0) {

			$categoryID = $_GET['categoryID'];
			$pageArray = $wikiTools->getCategoryPages($categoryID);

			echo("<h3>".$wikiTools->getCategoryName($categoryID)." Pages: </h3>");
	
			if (isset($pageArray)) {

				echo("<ul>");

				foreach($pageArray as $page) {

					echo("<li><a href='wikiPage.php?wikiPageID=".$page['wikiPageID']."&categoryID=".$categoryID."'>".$page['title']."</a>");

				}

				echo("</ul>");

			} else {

				echo("<ul><li><em>There are currently no pages in this category!</em></li></ul><br />");

			}

			if ($subCategories = $wikiTools->getCategoryList($categoryID)) {

				echo("<div class='subCategoryPages'>");
				echo("<h4>Sub Category Pages</h4>");

				foreach($subCategories as $subCategory) {
					
					echo("<h4><a href='index.php?categoryID=".$subCategory['wikiCategoryID']."'>".$subCategory['name']."</a></h4>");

					if ($pageArray = $wikiTools->getCategoryPages($subCategory['wikiCategoryID'])) {

						echo("<ul>");
						
						foreach($pageArray as $page) {

						echo("<li><a href='wikiPage.php?wikiPageID=".$page['wikiPageID']."&categoryID=".$categoryID."'>".$page['title']."</a>");

						}

						echo("</ul><br />");

					} else {

						echo("<ul><li><em>There are currently no pages in this category!</em></li></ul><br />");

					}


				}

				echo("</div>");

			}

		} else {

			$categoryID = 0;
			$pageArray = $pageTools->getDynamicContent($pageTools->getPageIDbyDirectLink("wiki/index.php"));

			echo($pageTools->matchTags($pageArray['text']));

		}

	?>

</div>
