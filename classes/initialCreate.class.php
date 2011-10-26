<?php

	require_once($fullPath."/classes/dbConn.class.php");
	
	class initialInstallWiki {

		public function createTables() {

			$db = new dbConn();

			$query = "

				CREATE TABLE wiki_pages (
					wikiPageID INT NOT NULL AUTO_INCREMENT,
					wikiPageTypeID INT,
					PRIMARY KEY(wikiPageID)
				); ";

			if ($db->mysqli->query($query)) {

				echo("wiki table created<br />");

			}

			else {

				echo($db->mysqli->error."<br />");

			}

			$query = "

				CREATE TABLE wiki_pageTypes (
						wikiPageTypeID INT NOT NULL AUTO_INCREMENT,
						name TEXT,
						description TEXT,
						PRIMARY KEY(wikiPageTypeID)
					); ";

			if ($db->mysqli->query($query)) {

				echo("wiki_pageTypes table created<br />");

			} else {

				echo($db->mysqli->error."<br />");

			}

			$query = "

				CREATE TABLE wiki_pageTypeDefinitions (
						wikiPageTypeDefinitionID INT NOT NULL AUTO_INCREMENT,
						wikiPageTypeID INT,
						headingOrder INT,
						heading TEXT,
						description TEXT,
						contentType TEXT,
						PRIMARY KEY(wikiPageTypeDefinitionID)
					); ";

			if ($db->mysqli->query($query)) {

				echo("wiki_pageTypeDefinitions table created <br />");

			} else {

				echo($db->mysqli->error."<br />");

			}

			$query = "

				CREATE TABLE wiki_pageContents (
						wikiPageTypeDefinitionID INT,
						wikiPageID INT,
						content TEXT,
						PRIMARY KEY(wikiPageID,wikiPageTypeDefinitionID)
					); ";

			if ($db->mysqli->query($query)) {

				echo("wiki_pageContent table created");

			} else {

				echo($db->mysqli->error."<br />");

			}

		}

		public function populateTables() {

			$db = new dbConn();

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
			
			if ($db->checkExists("adminContent","name","Wiki Module")) {

				echo("adminContent already populated with the Wiki Module <br />");

			}

			else {

				$query = "

					INSERT INTO adminContent (
							name,
							link,
							category
						) values (
							'Wiki Module',
							'wiki/admin/wikiModule.php',
							'main'
						);";

				if ($db->mysqli->query($query)) {

					echo("adminContent populated <br />");

				}

			}

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
