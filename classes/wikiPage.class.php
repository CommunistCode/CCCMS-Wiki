<?php

	require_once($fullPath . "/classes/dbConn.class.php");

	class wikiPage {

		var $wikiPageArray;

		public function wikiPage($id) {

			$db = new dbConn();

			$query = "SELECT * FROM
									wiki_pages,
									wiki_pageContents 
								WHERE  
									wiki_pages.wikiPageID = '".$id."' AND 
									wiki_pages.wikiPageID = wiki_pageContents.wikiPageID";
			
			$result = $db->mysqli->query($query) or die($db->mysqli->error);
			$data = $result->fetch_assoc();

			$query = "SELECT 
									wiki_pageContents.content, 
									wiki_pageTypeDefinitions.heading 
								FROM 
									wiki_pageTypeDefinitions, 
									wiki_pageContents  
								WHERE 
									wikiPageTypeID = '".$data['wikiPageTypeID']."' AND 
									wiki_pageContents.wikiPageTypeDefinitionID = wiki_pageTypeDefinitions.wikiPageTypeDefinitionID 
								ORDER BY 
									headingOrder ASC";

			$result = $db->mysqli->query($query) or die($db->mysqli->error);

			$i = 0;

			while ($row = $result->fetch_assoc()) {

				$wikiPageArray[$i]['heading'] = $row['heading'];
				$wikiPageArray[$i]['content'] = $row['content'];

				$i++;				

			}

			$this->wikiPageArray = $wikiPageArray;
			
		}

		public function getWikiPageArray() {

			return $this->wikiPageArray;

		}
		
	}

?>
