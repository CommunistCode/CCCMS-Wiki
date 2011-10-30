<?php

	class wikiAdminTools {

		public function addCategory($categoryName,$parentID) {

			$db = new dbConn();

			return	$db->insert("wiki_categories","name,parentCategoryID","'".$categoryName."',".$parentID);

		}

		public function deleteCategory($categoryID) {

			$db = new dbConn();

			return $db->delete("wiki_categories","wikiCategoryID=".$categoryID);

		}

		public function getAllCategories() {

			$db = new dbConn();

			$result = $db->select("wikiCategoryID,name","wiki_categories", 0);

			$i = 0;

			while ($row = $result->fetch_assoc()) {

				$categoryArray[$i]['wikiCategoryID'] = $row['wikiCategoryID'];
				$categoryArray[$i]['categoryName'] = $row['name'];

				$i++;

			}

			return $categoryArray;

		}

		public function getAllTemplates() {

			$db = new dbConn();

			$result = $db->select("wikiTemplateID,name","wiki_templates");
			
			for ($i=0; $i<$result->num_rows; $i++) {

				$templateArray[$i] = $result->fetch_assoc();

			}

			return $templateArray;


		}

		public function createTemplate($name, $description, $templateArray) {

			$db = new dbConn();

			$db->insert("wiki_templates",
									"name,description",
									"'".$name."','".$description."'"
								 );

			$newTemplateID = $db->mysqli->insert_id;

			for ($i=0; $i<count($templateArray); $i++) {

				$this->addDefinition($newTemplateID,
														 $templateArray[$i]['heading'],
														 $templateArray[$i]['description'],
														 $templateArray[$i]['dataType']
														);

			}

		}

		public function addDefinition($templateID, $heading, $description, $dataType) {

			$db = new dbConn();

			$order=0;

			$db->insert("wiki_templateDefinitions",
									"wikiTemplateID,
									 heading,
									 description,
									 headingOrder,
									 dataType",
									 $templateID.",
									 '".$heading."',
									 '".$description."',
									 ".$order.",
									 '".$dataType."'"
									);

		}

		public function deleteDefinition($templateID, $definitionID) {

			$db = new dbConn();

			$db->delete("wiki_templateDefinitions",
									"wikiTemplateID=".$templateID." 
									 AND 
									 wikiTemplateDefinitionID=".$definitionID
								 );


		}	

		public function deleteTemplate($templateID) {

			$db = new dbConn();

			$db->delete("wiki_templates",
									"wikiTemplateID=".$templateID
								 );

			$db->delete("wiki_templateDefinitions",
									"wikiTemplateID=".$templateID
								 );

		}

	}

?>
