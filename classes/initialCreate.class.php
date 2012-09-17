<?php

	require_once($fullPath."/classes/dbConn.class.php");
	require_once($fullPath."/admin/classes/adminDBTools.class.php");
	require_once($fullPath."/classes/dbTools.class.php");

	class initialInstallWiki {

		public function createTables() {

			$db = new dbConn();
			$dbTools = new dbTools();

			$i=0;

			$tableName = "wiki_categories";

			$tableDefinition[$i]['name'] = "wikiCategoryID";
			$tableDefinition[$i]['definition'] = "INT NOT NULL AUTO_INCREMENT";

			$tableDefinition[++$i]['name'] = "parentCategoryID";
			$tableDefinition[$i]['definition'] = "INT";

			$tableDefinition[++$i]['name'] = "name";
			$tableDefinition[$i]['definition'] = "TEXT";
			
			$primaryKey = "wikiCategoryID";
			
			$dbTools->newTable($tableName,$tableDefinition,$primaryKey);
			unset($tableDefinition);

			$i=0;

			$tableName = "wiki_pages";

			$tableDefinition[$i]['name'] = "wikiPageID";
			$tableDefinition[$i]['definition'] = "INT NOT NULL AUTO_INCREMENT";

			$tableDefinition[++$i]['name'] = "wikiTemplateID";
			$tableDefinition[$i]['definition'] = "INT";

			$tableDefinition[++$i]['name'] = "wikiCategoryID";
			$tableDefinition[$i]['definition'] = "INT";

			$tableDefinition[++$i]['name'] = "title";
			$tableDefinition[$i]['definition'] = "TEXT";

			$primaryKey = "wikiPageID";

			$dbTools->newTable($tableName,$tableDefinition,$primaryKey);
			unset($tableDefinition);

			$i=0;

			$tableName = "wiki_templates";

			$tableDefinition[$i]['name'] = "wikiTemplateID";
			$tableDefinition[$i]['definition'] = "INT NOT NULL AUTO_INCREMENT";

			$tableDefinition[++$i]['name'] = "name";
			$tableDefinition[$i]['definition'] = "TEXT";

			$tableDefinition[++$i]['name'] = "description";
			$tableDefinition[$i]['definition'] = "TEXT";

			$primaryKey = "wikiTemplateID";

			$dbTools->newTable($tableName,$tableDefinition,$primaryKey);
			unset($tableDefinition);

			$i=0;

			$tableName = "wiki_templateDefinitions";

			$tableDefinition[$i]['name'] = "wikiTemplateDefinitionID";
			$tableDefinition[$i]['definition'] = "INT NOT NULL AUTO_INCREMENT";

			$tableDefinition[++$i]['name'] = "wikiTemplateID";
			$tableDefinition[$i]['definition'] = "INT";

			$tableDefinition[++$i]['name'] = "headingOrder";
			$tableDefinition[$i]['definition'] = "INT";

			$tableDefinition[++$i]['name'] = "heading";
			$tableDefinition[$i]['definition'] = "TEXT";

			$tableDefinition[++$i]['name'] = "description";
			$tableDefinition[$i]['definition'] = "TEXT";

			$tableDefinition[++$i]['name'] = "dataType";
			$tableDefinition[$i]['definition'] = "TEXT";

			$primaryKey = "wikiTemplateDefinitionID";

			$dbTools->newTable($tableName,$tableDefinition,$primaryKey);
			unset($tableDefinition);

			$i=0;

			$tableName = "wiki_pageContents";

			$tableDefinition[$i]['name'] = "wikiTemplateDefinitionID";
			$tableDefinition[$i]['definition'] = "INT";

			$tableDefinition[++$i]['name'] = "wikiPageID";
			$tableDefinition[$i]['definition'] = "INT";
		
			$tableDefinition[++$i]['name'] = "wikiTemplateID";
			$tableDefinition[$i]['definition'] = "INT";
			
			$tableDefinition[++$i]['name'] = "content";
			$tableDefinition[$i]['definition'] = "TEXT";

			$tableDefinition[++$i]['name'] = "date";
			$tableDefinition[$i]['definition'] = "INT";

			$tableDefinition[++$i]['name'] = "isCurrent";
			$tableDefinition[$i]['definition'] = "TINYINT";

			$tableDefinition[++$i]['name'] = "memberID";
			$tableDefinition[$i]['definition'] = "INT";
	
			$primaryKey = "wikiPageID,wikiTemplateID,wikiTemplateDefinitionID,date";

			$dbTools->newTable($tableName,$tableDefinition,$primaryKey);
			unset($tableDefinition);

		}

		public function populateTables() {

			$db = new dbConn();
			$adminDBTools = new adminDBTools();
		
			if ($db->checkExists("version","module","wiki")) {

				echo("version already populated<br />");

			}

			else {

				
				if ($db->insert("version","module,version,theme","'wiki','1.0.0','default'")) {

					echo("version populated<br />");

				}

			}
		
			$adminDBTools->newContent("Wiki Module","wiki/admin/wikiModule.php","main");
			$adminDBTools->newContent("Create Template","wiki/admin/createTemplate.php","Wiki Module");
			$adminDBTools->newContent("Delete Template","wiki/admin/deleteTemplate.php","Wiki Module");
			$adminDBTools->newContent("Manage Categories","wiki/admin/manageCategories.php","Wiki Module");

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
