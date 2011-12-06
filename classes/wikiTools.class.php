<?php

	class wikiTools {

		public function countPagesInCategoryRecurring($categoryID, $runningTotal = 0) {

			$db = new dbConn();

			$result = $db->selectWhere("wikiCategoryID","wiki_categories","parentCategoryID=".$categoryID);

			while ($data = $result->fetch_assoc()) {

				$runningTotal = $this->countPagesInCategoryRecurring($data['wikiCategoryID'], $runningTotal);

			}
			
			$countResult = $db->selectWhere("COUNT(wikiPageID) as count","wiki_pages","wikiCategoryID=".$categoryID);

			if (isset($countResult)) {

				$thisCount = $countResult->fetch_assoc();
				$runningTotal = $runningTotal + $thisCount['count'];

			}

			return $runningTotal;

		}
		
		public function getHistory($wikiPageID,$definitionID) {

			$db = new dbConn();

			$result = $db->selectWhere("date,content,memberID,isCurrent","wiki_pageContents","wikiTemplateDefinitionID=".$definitionID." AND wikiPageID=".$wikiPageID." ORDER BY date ASC");

			$i=0;

			while ($row=$result->fetch_assoc()) {

				$historyArray[$i]['date'] = $row['date'];
				$historyArray[$i]['content'] = $row['content'];
				$historyArray[$i]['memberID'] = $row['memberID'];
				$historyArray[$i]['isCurrent'] = $row['isCurrent'];

				$i++;

			}

			return $historyArray;

		}
		
		public function getCategoryPages($id, $limit = NULL) {

			$db = new dbConn();

			$query = "SELECT DISTINCT 
									wp.title,
									wp.wikiPageID
								FROM
									wiki_pages wp
								LEFT JOIN
									wiki_pageContents wpc
								ON
									wp.wikiPageID = wpc.wikiPageID
								WHERE
									wp.wikiCategoryID = ".$id." 
								ORDER BY
									wp.title 
								ASC
							 ";

			if ($limit != NULL) {

				$query .= "LIMIT ".$limit;

			}

			$result = $db->mysqli->query($query);

			if ($result->num_rows != 0) {

				$i = 0;

				while ($row=$result->fetch_assoc()) {

					$wikiPageArray[$i]['wikiPageID'] = $row['wikiPageID'];
					$wikiPageArray[$i]['title'] = $row['title'];

					$i++;

				}
	
				return $wikiPageArray;

			}

		}
		
		public function getCategoryParentID($id) {

			$db = new dbConn();

			$result = $db->selectWhere("parentCategoryID","wiki_categories","wikiCategoryID=".$id);

			if ($result->num_rows>0) {
				
				$categoryArray = $result->fetch_assoc();

				return $categoryArray['parentCategoryID'];

			} else {

				return 0;

			}

		}

		public function getCategoryName($id) {

			$db = new dbConn();

			$result = $db->selectWhere("name","wiki_categories","wikiCategoryID=".$id);

			if ($result->num_rows>0) {
				
				$categoryArray = $result->fetch_assoc();

				return $categoryArray['name'];

			}

		}

		public function getCategoryList($parentCategoryID) {

			$db = new dbConn();

			$result = $db->selectWhere("name,wikiCategoryID","wiki_categories","parentCategoryID=".$parentCategoryID);

			$i = 0;

			while ($row = $result->fetch_assoc()) {
				
				$categoryArray[$i]['name'] = $row['name'];
				$categoryArray[$i]['wikiCategoryID'] = $row['wikiCategoryID'];

				$i++;

			}

			if (isset($categoryArray)) {
	
				return $categoryArray;

			}

		}

		public function sortCategories($sortedArray, $currentCategory = 0, $level = 0) {

      $db = new dbConn();

      if ($result = $db->selectWhere("wikiCategoryID,name,parentCategoryID","wiki_categories","parentCategoryID=".$currentCategory)) {

        while ($category=$result->fetch_assoc()) {

          $arraySize = count($sortedArray);

          $sortedArray[$arraySize]['categoryName'] = $category['name'];
					$sortedArray[$arraySize]['wikiCategoryID'] = $category['wikiCategoryID'];
          $sortedArray[$arraySize]['level'] = $level;

          $sortedArray = $this->sortCategories($sortedArray,$category['wikiCategoryID'],($level+1));

        }

      }

      return $sortedArray;

    }

    public function getSortedCategories() {

      $array = array();
      $sortedCategories = $this->sortCategories($array);

      return $sortedCategories;

    }


		
		public function renderCategorySelectOptions() {

			$categoryArray = $this->getSortedCategories();

			foreach($categoryArray as $category) {

			echo("<option value='".$category['wikiCategoryID']."'>");

				for ($i=0; $i<=$category['level']; $i++) {

					echo("-");

				}

				echo(" ".$category['categoryName']."</option>");

			}
		}

		public function insertContent($pageID,$templateID,$definitionID,$contents) {

			$db = new dbConn();

			$time = time();
			$member = unserialize($_SESSION['member']);

			$contents = addslashes($contents);

			if ($db->insert("wiki_pageContents",
									"wikiTemplateDefinitionID,
									 wikiPageID,
									 wikiTemplateID,
									 content,
									 date,
									 isCurrent,
									 memberID",
									 "'".$definitionID."',
									 '".$pageID."',
									 '".$templateID."',
									 '".$contents."',
									 ".$time.",
									 1,
									 ".$member->getID()."")) {


				$db->update("wiki_pageContents","isCurrent=0","isCurrent=1 AND wikiPageID=".$pageID." AND wikiTemplateDefinitionID=".$definitionID." AND date != ".$time);

			} else {

				 echo($db->mysqli->error);

			}


		}

		function insertImage($wikiID,$templateDefinitionID,$tmpImageLocation, $caption) {

			// Manage and upload image

			$db = new dbConn();

			$time = time();
			$member = unserialize($_SESSION['member']);

			try {
				
				$imageJiggle = new imageJiggle($tmpImageLocation);

			} catch (Exception $e) {

				die($e->getMessage());

			}

			$caption = addslashes($caption);

			$db->insert("wiki_pageImages",
									"wikiPageID,
									 wikiTemplateDefinitionID,
									 caption,
									 memberID,
									 date",
									 "'".$wikiID."',
									 '".$templateDefinitionID."',
									 '".$caption."',
									 '".$member->getID()."',
									 '".$time."'");

			$uniqueID = $db->mysqli->insert_id;

			$path = "wikiUserImages/".$uniqueID.".jpg";

			try {
			
				// Write thumbnail
				$imageJiggle->setDimensions(155,140);
				$imageJiggle->write("wikiUserImages/thumbs/",$uniqueID, 75);

				// Write full image
				$imageJiggle->setDimensions(690,490);
				$imageJiggle->write("wikiUserImages/full/",$uniqueID, 90);
				

			} catch (Exception $e) {

				die($e->getMessage());

			}

			$db->update("wiki_pageImages","type='".$imageInfo['mime']."'","imageID=".$uniqueID);

		}

		public function createPage($title,$template,$category) {

			$db = new dbConn();

			$db->insert("wiki_pages",
									"wikiTemplateID,
									 wikiCategoryID,
									 title",
									 $template.","
									 .$category.",
									 '".$title."'"
									);

			return $db->mysqli->insert_id;

		}

		public function getAllTemplates() {

			$db = new dbConn();

			$result = $db->select("wikiTemplateID,name","wiki_templates");

			for ($i=0; $i<$result->num_rows; $i++) {

				$templateArray[$i] = $result->fetch_assoc();

			}

			return $templateArray;

		}

		public function getCategoryTree($categoryID) {

			$treeArray = array();

			$i = 0;

			$treeArray[$i]['name'] = $this->getCategoryName($categoryID);
			$treeArray[$i]['id'] = $categoryID;

			while ($parentID = $this->getCategoryParentID($categoryID)) {

				$i++;

				$treeArray[$i]['name'] = $this->getCategoryName($parentID);
				$treeArray[$i]['id'] = $parentID;

				$categoryID = $parentID;

			}

			$z = 0;

			for ($i = (count($treeArray)-1); $i>=0; $i--) {

				$sortedTreeArray[$z]['name'] = $treeArray[$i]['name'];
				$sortedTreeArray[$z]['id'] = $treeArray[$i]['id'];
				$z++;

			}

			return $sortedTreeArray;

		}

		function generateInput($wikiContentType, $wikiContent) {

			switch($wikiContentType) {

				case "text":
					
					$inputElement = "<input class='textInput' name='newContent' type='text' value='".$wikiContent."' />";
					break;

				case "textarea":
					
					$inputElement = "<textarea rows='4' class='textInput' name='newContent' >".$wikiContent."</textarea>";
					break;

				case "image":

					$inputElement = "<label for='newPhoto'>Image Location: </label><input class='textInput' type='file' name='newImage' />";
					$inputElement .= "<br /><br />";
					$inputElement .= "<label for='caption'>Caption: </label><input class='textInput' type='text' name='imageCaption'/>";

					break;

			}

			return $inputElement;

		}

		function getImageDetails($imageID) {

			$db = new dbConn();

			$result = $db->selectWhere("wikiPageID, 
																	wikiTemplateDefinitionID, 
																	caption, 
																	date, 
																	memberID",
																 "wiki_pageImages",
																 "imageID=".$imageID);

			$image = $result->fetch_assoc();

			$imageDetails['wikiPageID'] = $image['wikiPageID'];
			$imageDetails['wikiTemplateDefinitionID'] = $image['wikiTemplateDefinitionID'];
			$imageDetails['caption'] = $image['caption'];
			$imageDetails['date'] = $image['date'];
			$imageDetails['memberID'] = $image['memberID'];

			$result = $db->selectWhere("*","wiki_pageImages","wikiPageID=".$image['wikiPageID']." AND wikiTemplateDefinitionID=".$image['wikiTemplateDefinitionID']." ORDER BY date ASC");

			$imageSetArray = array();

			while ($image = $result->fetch_assoc()) {

				array_push($imageSetArray, $image);

				if ($image['imageID'] == $imageID) {

					$imageDetails['imageIndex'] = count($imageSetArray) - 1;

				}

			}
			
			$imageDetails['noImagesInSet'] = count($imageSetArray);
			
			if (($imageDetails['imageIndex'] + 1) <= ($imageDetails['noImagesInSet'] - 1)) {

				$imageDetails['nextImage'] = $imageSetArray[$imageDetails['imageIndex'] + 1]['imageID'];

			}

			if (($imageDetails['imageIndex'] - 1) >= 0) {

				$imageDetails['prevImage'] = $imageSetArray[$imageDetails['imageIndex'] - 1]['imageID'];

			}

			return $imageDetails;

		}

		function getWikiTitle($wikiID) {

			$db = new dbConn();

			$result = $db->selectWhere("title","wiki_pages","wikiPageID=".$wikiID);

			$data = $result->fetch_assoc();

			return $data['title'];

		}
		
	}

?>
