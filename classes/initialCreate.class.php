<?php

	require_once($fullPath."/classes/dbConn.class.php");
	require_once($fullPath."/admin/classes/adminDBTools.class.php");
	require_once($fullPath."/classes/dbTools.class.php");

	class initialInstallWiki {

		public function createTables() {

			$db = new dbConn();
			$dbTools = new dbTools();

			$i=0;

			$tableName = "wiki_category";

			$tableDefinition[$i]['name'] = "wikiCategoryID";
			$tableDefinition[$i]['definition'] = "INT NOT NULL AUTO_INCREMENT";

			$tableDefinition[++$i]['name'] = "parentCategoryID";
			$tableDefinition[$i]['definition'] = "INT";

			$tableDefinition[++$i]['name'] = "name";
			$tableDefinition[$i]['definition'] = "TEXT";
			
			$primaryKey = "wikiCategoryID";
			
			$dbTools->newTable($tableName,$tableDefinition,$primaryKey);

			$i=0;

			$tableName = "wiki_pages";

			$tableDefinition[$i]['name'] = "wikiPageID";
			$tableDefinition[$i]['definition'] = "INT NOT NULL AUTO_INCREMENT";

			$tableDefinition[++$i]['name'] = "wikiTemplateID";
			$tableDefinition[$i]['definition'] = "INT";

			$tableDefinition[++$i]['name'] = "wikiCategoryID";
			$tableDefinition[$i]['definition'] = "INT";

			$primaryKey = "wikiPageID";

			$dbTools->newTable($tableName,$tableDefinition,$primaryKey);

			$query = "

				CREATE TABLE wiki_templates (
						wikiTemplateID INT NOT NULL AUTO_INCREMENT,
						name TEXT,
						description TEXT,
						PRIMARY KEY(wikiTemplateID)
					); ";

			if ($db->mysqli->query($query)) {

				echo("wiki_pageTypes table created<br />");

			} else {

				echo($db->mysqli->error."<br />");

			}

			$query = "

				CREATE TABLE wiki_templateDefinitions (
						wikiTemplateDefinitionID INT NOT NULL AUTO_INCREMENT,
						wikiTemplateID INT,
						headingOrder INT,
						heading TEXT,
						description TEXT,
						dataType TEXT,
						PRIMARY KEY(wikiTemplateDefinitionID)
					); ";

			if ($db->mysqli->query($query)) {

				echo("wiki_pageTypeDefinitions table created <br />");

			} else {

				echo($db->mysqli->error."<br />");

			}

			$query = "

				CREATE TABLE wiki_pageContents (
						wikiTemplateDefinitionID INT,
						wikiPageID INT,
						wikiTemplateID INT,
						content TEXT,
						PRIMARY KEY(wikiPageID,wikiTemplateID,wikiTemplateDefinitionID)
					); ";

			if ($db->mysqli->query($query)) {

				echo("wiki_pageContent table created");

			} else {

				echo($db->mysqli->error."<br />");

			}

		}

		public function populateTables() {

			$db = new dbConn();
			$adminDBTools = new adminDBTools();
		
			if ($db->checkExists("version","module","wiki")) {

				echo("version already populated<br />");

			}

			else {

				$query = "

					INSERT INTO version (
						module,
						version,
						theme
					) values (
						'wiki',
						'1.0.0',
						'default'
					); ";

				if ($db->mysqli->query($query)) {

					echo("version populated<br />");

				}

			}
		
			$adminDBTools->newContent("Wiki Module","wiki/admin/wikiModules.php","main");
			$adminDBTools->newContent("Create Template","wiki/admin/createTemplate.php","Wiki Module");
			$adminDBTools->newContent("Delete Template","wiki/admin/deleteTemplate.php","Wiki Module");

			if ($db->checkExists("dContent","title","Wiki")) {

				echo("dContent already populated with wiki link <br />");

			}

			else {

				$query = "

					INSERT INTO dContent (
						title,
						linkName,
						directLink
					) values (
						'Wiki',
						'Wiki',
						'wiki/index.php'
					);";

				if ($db->mysqli->query($query)) {

					echo("dContent populated <br />");

				}

			}

		}

	}

?>
