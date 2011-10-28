<?php

	require_once($fullPath . "/classes/dbConn.class.php");

	class wikiPage {

		var $wikiPageArray;

		public function wikiPage($id) {

			$db = new dbConn();

			$query = "SELECT wikiTemplateID from wiki_pages WHERE wikiPageID='".$id."'";

			$result = $db->mysqli->query($query);
			$data = $result->fetch_assoc();

			$templateID = $data['wikiTemplateID'];

			$query = "SELECT 
									wpc.content,
									wtd.heading
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
									wtd.wikiTemplateID = ".$templateID." 
								ORDER BY
									headingOrder
								ASC";

			$result = $db->mysqli->query($query) OR die($db->mysqli->error);

			$i = 0;

			while ($row = $result->fetch_assoc()) {

				$wikiPageArray[$i]['heading'] = $row['heading'];

				if (isset($row['content'])) {

					$wikiPageArray[$i]['content'] = $row['content'];

				}	else {
				
					$wikiPageArray[$i]['content'] = "";

				}

				$i++;

			}

			$this->wikiPageArray = $wikiPageArray;
			
		}

		public function getWikiPageArray() {

			return $this->wikiPageArray;

		}
		
	}

?>
