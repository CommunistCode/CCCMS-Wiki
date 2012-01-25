<?php

  require_once($fullPath."/classes/dbConn.class.php");
  require_once($fullPath."/classes/pdoConn.class.php");

	class wikiTools {

    private $pdoConn;

    function __construct() {

      $this->pdoConn = new pdoConn();

    }

		public function countPagesInCategoryRecurring($categoryID, $runningTotal = 0) {

      $field = "wikiCategoryID";
      $table = "wiki_categories";

      $where[0]['column'] = "parentCategoryID";
      $where[0]['value'] = $categoryID;

			$result = $this->pdoConn->select($field,$table,$where);

      foreach($result as $data) {

				$runningTotal = $this->countPagesInCategoryRecurring($data['wikiCategoryID'], $runningTotal);

			}
			
      $field = "COUNT(wikiPageID) as count";
      $table = "wiki_pageCategories";

      $where[0]['column'] = "wikiCategoryID";
      $where[0]['value'] = $categoryID;

			$countResult = $this->pdoConn->select($field,$table,$where);

			if (count($countResult) > 0) {

				$runningTotal = $runningTotal + $countResult[0]['count'];

			}

			return $runningTotal;

		}
		
		public function getHistory($wikiPageID,$definitionID) {

      $fields = array("date","content","memberID","isCurrent");
      $table = "wiki_pageContents";

      $where[0]['column'] = "wikiTemplateDefinitionID";
      $where[0]['value'] = $definitionID;

      $where[1]['column'] = "wikiPageID";
      $where[1]['value'] = $wikiPageID;

      $orderBy = "date DESC";
    
			$result = $this->pdoConn->select($fields,$table,$where,$orderBy);

			$i=0;

      foreach($result as $row) {

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
                  wiki_pageCategories wpcat
                NATURAL JOIN 
									wiki_pages wp
								LEFT JOIN
									wiki_pageContents wpcon
								ON
									wp.wikiPageID = wpcon.wikiPageID
								WHERE
									wpcat.wikiCategoryID = ".$id."
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

      $field = "parentCategoryID";
      $table = "wiki_categories";

      $where[0]['column'] = "wikiCategoryID";
      $where[0]['value'] = $id;

			$result = $this->pdoConn->select($field,$table,$where);
			
      if (count($result)>0) {
				
				return $result[0]['parentCategoryID'];

			} else {

				return 0;

			}

		}

		public function getCategoryName($id) {

      $field = "name";
      $table = "wiki_categories";

      $where[0]['column'] = "wikiCategoryID";
      $where[0]['value'] = $id;

			$result = $this->pdoConn->select($field,$table,$where);

			if (count($result)>0) {
				
				return $result[0]['name'];

			}

		}

		public function getCategoryList($parentCategoryID) {

      $fields = array("name","wikiCategoryID");
      $table = "wiki_categories";

      $where[0]['column'] = "parentCategoryID";
      $where[0]['value'] = $parentCategoryID;

			$result = $this->pdoConn->select($fields,$table,$where);
      
      $i = 0;

      foreach($result as $row) {
				
				$categoryArray[$i]['name'] = $row['name'];
				$categoryArray[$i]['wikiCategoryID'] = $row['wikiCategoryID'];

				$i++;

			}

			if (isset($categoryArray)) {
	
				return $categoryArray;

			}

		}

		public function sortCategories($sortedArray, $currentCategory = 0, $level = 0) {

      $fields = array("wikiCategoryID","name","parentCategoryID");
      $table = "wiki_categories";

      $where[0]['column'] = "parentCategoryID";
      $where[0]['value'] = $currentCategory;

      $result = $this->pdoConn->select($fields,$table,$where);
      
      if (count($result) > 0) {

        foreach($result as $category) {

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


        $table = "wiki_pageContents";
        
        $set[0]['column'] = "isCurrent";
        $set[0]['value'] = 0;

        $where[0]['column'] = "wikiPageID";
        $where[0]['value'] = $pageID;

        $where[1]['column'] ="isCurrent";
        $where[1]['value'] = 1;

        $where[2]['column'] = "wikiTemplateDefinitionID";
        $where[2]['value'] = $definitionID;

        $where[3]['column'] = "date";
        $where[3]['operator'] = "!=";
        $where[3]['value'] = $time;

				$this->pdoConn->update($table,$set,$where);

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
			
				$mimeType = $imageJiggle->getMimeType();

			} catch (Exception $e) {

				die($e->getMessage());

			}

      $table = "wiki_pageImages";
      
      $set[0]['column'] = "type";
      $set[0]['value'] = $mimeType;

      $where[0]['column'] = "imageID";
      $where[0]['value'] = $uniqueID;

			$this->pdoConn->update($table,$set,$where);

		}

		public function createPage($title,$template,$categoryArray) {

			$db = new dbConn();

			$db->insert("wiki_pages",
									"wikiTemplateID,
									 title",
									 $template.",
                   '".$title."'"
									);

			$id = $db->mysqli->insert_id;

      if (gettype($categoryArray) != "array") {

        $categoryID = $categoryArray;
        unset($categoryArray);
        $categoryArray = array();
        $categoryArray[] = $categoryID;

      }

      foreach($categoryArray as $category) {

        $db->insert("wiki_pageCategories","wikiPageID,wikiCategoryID",$id.",".$category);

      }

      return $id;

		}

		public function getAllTemplates() {

      $fields = array("wikiTemplateID","name");
      $table = "wiki_templates";

			$result = $this->pdoConn->select($fields,$table);

			for ($i=0; $i<count($result); $i++) {

        $resultRow = array_shift($result);
				$templateArray[$i] = $resultRow;

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

      $fields = array("wikiPageID","wikiTemplateDefinitionID","caption","date","memberID");
      $table = "wiki_pageImages";

      $where[0]['column'] = "imageID";
      $where[0]['value'] = $imageID;

			$result = $this->pdoConn->select($fields,$table,$where);

			$image = array_shift($result);

			$imageDetails['wikiPageID'] = $image['wikiPageID'];
			$imageDetails['wikiTemplateDefinitionID'] = $image['wikiTemplateDefinitionID'];
			$imageDetails['caption'] = $image['caption'];
			$imageDetails['date'] = $image['date'];
			$imageDetails['memberID'] = $image['memberID'];

      $field = "*";
      $table = "wiki_pageImages";

      $where[0]['column'] = "wikiPageID";
      $where[0]['value'] = $image['wikiPageID'];

      $where[1]['column'] = "wikiTemplateDefinitionID";
      $where[1]['value'] = $image['wikiTemplateDefinitionID'];

      $orderBy = "date ASC";

			$result = $this->pdoConn->select($field,$table,$where,$orderBy);

			$imageSetArray = array();

      foreach($result as $image) {

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

      $field = "title";
      $table = "wiki_pages";

      $where[0]['column'] = "wikiPageID";
      $where[0]['value'] = $wikiID;

			$result = $this->pdoConn->select($field, $table, $where);

			return $result[0]['title'];

		}
		
	}

?>
