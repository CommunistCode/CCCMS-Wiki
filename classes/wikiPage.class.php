<?php

	class wikiPage {

		var $wikiPageArray;
		var $id;
		var $title;
		var $categoryArray;
		var $templateID;

		public function wikiPage($id) {

			$this->id = $id;

			$db = new dbConn();

			$query = "SELECT wikiTemplateID,title from wiki_pages WHERE wikiPageID='".$this->id."'";

			$result = $db->mysqli->query($query);
			$data = $result->fetch_assoc();

			$this->templateID = $data['wikiTemplateID'];
			$this->title = $data['title'];

      $result = $db->selectWhere("wikiCategoryID","wiki_pageCategories","wikiPageID=".$this->id."");

      foreach($result->fetch_assoc() as $row) {

        $this->categoryArray[] = $row['wikiCategoryID'];

      }

			$query = "SELECT 
									wpc.content,
									wtd.heading,
									wtd.wikiTemplateDefinitionID,
									wtd.dataType 
								FROM 
									wiki_templateDefinitions wtd
								LEFT JOIN
									wiki_pageContents wpc
								ON
									wpc.wikiTemplateDefinitionID = wtd.wikiTemplateDefinitionID
								AND
									wpc.wikiTemplateID = wtd.wikiTemplateID
								AND
									wpc.wikiPageID = ".$id."
								WHERE
									wtd.wikiTemplateID = ".$this->templateID."
								AND
									(wpc.isCurrent = 1
									OR
									wpc.isCurrent IS NULL)
								ORDER BY
									headingOrder
								ASC
								";

			$result = $db->mysqli->query($query) OR die($db->mysqli->error);

			$i = 0;
			
			while ($row = $result->fetch_assoc()) {

				$wikiPageArray[$i]['heading'] = $row['heading'];
				$wikiPageArray[$i]['dataType'] = $row['dataType'];
				$wikiPageArray[$i]['definitionID'] = $row['wikiTemplateDefinitionID'];

				if (isset($row['content'])) {

					$wikiPageArray[$i]['content'] = $row['content'];

				}	else {
				
					$wikiPageArray[$i]['content'] = "";

				}

				$i++;

			}
			
			$this->wikiPageArray = $wikiPageArray;
			
		}

		function getImages($definitionID) {

			$query = "SELECT  
									imageID, type, caption, date, memberID  
								FROM 
									wiki_pageImages 
								WHERE 
									wikiPageID=".$this->id." 
								AND 
									wikiTemplateDefinitionID=".$definitionID."
							 ";

			$db = new dbConn();

			$result = $db->mysqli->query($query); 
			
			if ($result->num_rows != 0) {

				$imageArray = array();

				for ($i=0; $i<$result->num_rows; $i++) {

					$row = $result->fetch_assoc();

					$imageArray[$i]['imageID'] = $row['imageID'];
					$imageArray[$i]['type'] = $row['type'];
					$imageArray[$i]['caption'] = $row['caption'];
					$imageArray[$i]['date'] = $row['date'];
					$imageArray[$i]['memberID'] = $row['memberID'];

				}

				return $imageArray;

			} else {

				return "No images have been added yet!";

			}

		}

		public function getWikiPageArray() {

			return $this->wikiPageArray;

		}

		public function getTitle() {

			return $this->title;

		}

		public function getCategoryArray() {

			return $this->categoryArray;

		}

		public function getID() {

			return $this->id;

		}

		public function getTemplateID() {

			return $this->templateID;

		}
		
	}

?>
