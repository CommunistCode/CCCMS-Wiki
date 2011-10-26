<?php

	class wikiTools {

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
