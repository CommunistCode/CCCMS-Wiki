<?php

	$categoryID = $_GET['categoryID'];
	
	$categoryBarArray[0] = $_GET['categoryID'];

	$i = 0;

	while ($wikiTools->getCategoryParentID($categoryID) != 0) {
		
		$i++;
	
		$parentCategory = $wikiTools->getCategoryParentID($categoryID);;
		
		$categoryBarArray[$i] = $parentCategory;
		$categoryID = $parentCategory;

	}

	if ($categoryBarArray[0] != 0) {

		$categoryBarArray[($i+1)] = 0;

	}

?>

<div class='wikiCategoryBar'>
	<h3>

		<?php
			
			if (isset($categoryBarArray)) {

				for($i=(count($categoryBarArray)-1); $i>=0; $i--) {

					if ($categoryBarArray[$i] == 0) {

						echo("<a href='index.php?categoryID=0'>Base</a>");

					} else {
						
						echo("<a href='index.php?categoryID=".$categoryBarArray[$i]."'>".$wikiTools->getCategoryName($categoryBarArray[$i])."</a>");

					}
					
					if ($i != 0) {

						echo(" > ");

					}

				}

			}

		?>

	</h3>
</div>
