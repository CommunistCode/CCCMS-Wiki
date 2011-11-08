<?php

	class wikiTools {

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
		
		public function getCategoryPages($id) {

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
							 ";

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
		
	}

?>
