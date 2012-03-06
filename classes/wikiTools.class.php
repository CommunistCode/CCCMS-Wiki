<?php

  require_once(FULL_PATH."/helperClasses/categoryManager/categoryManager.class.php");

	class wikiTools {

    private $pdoConn;
    private $cM;

    function __construct() {

      $this->pdoConn = new pdoConn();

      $fields = array("wikiCategoryID","name","parentCategoryID");      
      $table = "wiki_categories";

      $result = $this->pdoConn->select($fields,$table);
      
      $this->cM = new categoryManager($result,"wikiCategoryID","parentCategoryID","name");

    }

    public function getCategoryParentID($id) {

      return $this->cM->getCategoryParentID($id);

    }

    public function countPagesInCategoryRecurring($categoryID) {

      $childCategoryArray = $this->cM->getChildCategoriesRecurring($categoryID);

      $field = "COUNT(wikiPageID) as count";
      $table = "wiki_pageCategories";

      $i = 0;

      foreach ($childCategoryArray as $childCategoryID) {
      
        $where[$i]['joinOperator'] = "OR";
        $where[$i]['column'] = "wikiCategoryID";
        $where[$i]['value'] = $childCategoryID;

        $i++;

      }
 
      $where[$i]['joinOperator'] = "OR";
      $where[$i]['column'] = "wikiCategoryID";
      $where[$i]['value'] = $categoryID;
 
      $countResult = $this->pdoConn->select($field,$table,$where);

      return $countResult[0]['count'];
      

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
		
		public function getCategoryName($id) {

      return $this->cM->getCategoryName($id);    

		}

		public function getCategoryList($parentCategoryID) {

      return $this->cM->getChildCategories($parentCategoryID);
     
		}

    public function getSortedCategories() {

      return $this->cM->makeCategoryTreeArray();

    }


		
		public function renderCategorySelectOptions() {

			$categoryArray = $this->cM->makeCategoryTreeArray();

			foreach($categoryArray as $category) {

			echo("<option value='".$category['wikiCategoryID']."'>");

				for ($i=0; $i<=$category['level']; $i++) {

					echo("-");

				}

				echo(" ".$category['name']."</option>");

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

    public function deleteImage($imageID) {

      // Very primitive image delete function

      $db = new dbConn();

      $db->delete("wiki_pageImages","imageID=".$imageID);

      unlink("wikiUserImages/thumbs/".$imageID.".jpg");
      unlink("wikiUserImages/full/".$imageID.".jpg");

      return;

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

		public function getCategoryPath($categoryID) {

      return $this->cM->getCategoryPath($categoryID);
	
		}

		function generateInput($wikiContentType, $wikiContent) {

			switch($wikiContentType) {

				case "text":
					
					$inputElement = "<input class='textInput' name='newContent' type='text' value='".$wikiContent."' />";
					break;

				case "textarea":
					
					$inputElement = "<textarea rows='10' class='textInput' name='newContent' >".$wikiContent."</textarea>";
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
