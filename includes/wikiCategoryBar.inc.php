<?php

	if (isset($_GET['categoryID'])) {

		$categoryID = $_GET['categoryID'];

	} else if (isset($_GET['wikiPageID'])) {

		$categoryArray = $wikiPage->getCategoryArray();
    $categoryID = $categoryArray[0];

	} else if (isset($_POST['pageCategory'])) {

		$categoryID = $_POST['pageCategory'];

	} else {

		$categoryID = 0;

	}
	
	$categoryBarArray[0] = $categoryID[0];

	$i = 0;

	while ($wikiTools->getCategoryParentID($categoryID[0]) != 0) {
		
		$i++;
	
		$parentCategory = $wikiTools->getCategoryParentID($categoryID[0]);;
		
		$categoryBarArray[$i] = $parentCategory;
		$categoryID = $parentCategory;

	}

	if ($categoryBarArray[0] != 0) {

		$categoryBarArray[($i+1)] = 0;

	}

?>

<div class='wikiCategoryBar'>

		<?php
			
			if (isset($categoryBarArray)) {

				for($i=(count($categoryBarArray)-1); $i>=0; $i--) {

					if ($categoryBarArray[$i] == 0) {

						echo("<a href='index.php?categoryID=0'>Home</a>");

					} else {
						
						echo("<a href='index.php?categoryID=".$categoryBarArray[$i]."'>".$wikiTools->getCategoryName($categoryBarArray[$i])."</a>");

					}
					
					if ($i != 0) {

						echo(" > ");

					}

				}

			}

			if (isset($_GET['wikiPageID']) || isset($_POST['doCreatePage'])) {

				if (isset($_POST['viewHistory'])) {

					echo(" > <strong>Viewing History</strong>");

				} else {
			
					echo(" > <strong>Viewing Page</strong>");

				}

			}

		?>

</div>
