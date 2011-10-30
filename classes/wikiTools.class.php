<?php

	class wikiTools {

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
		
		function getPage($id) {

			$pageContent = $this->getContent($id);
			$pageHeadings = $this->getHeadings($id);
		
			for ($i=0; $i<count($pageContent); $i++) {

				$pageArray[$i]['heading'] = $pageContent[$i];
				$pageArray[$i]['content'] = $pageHeadings[$i];
	
			}

			return $pageArray;

		}

		function getContent($id) {

			$db = new dbConn();
			
			$result = $db->selectWhere("*","wiki_pages","wikiPageID='".$id."'");
			$page = $result->fetch_assoc();

			$pageContent = explode(";",$page['content']);
		
			return $pageContent;

		}

		function getHeadings($id) {

			$db = new dbConn();

			$result = $db->selectWhere("*","wiki_pageTypes","wikiPageTypeID='".$page['wikiPageTypeID']."'");
			$pageDefinition = $result->fetch_assoc();

			$pageHeadings = explode(";",$pageDefinition);

			return $pageHeadings;

		}

	}

?>
