<?php

	require_once($fullPath."/classes/dbConn.class.php");
	
	class initialInstallMantisWiki {

		public function createTables() {

			$db = new dbConn();

			$query = "

				CREATE TABLE wiki_pages (
					wikiPageID INT NOT NULL AUTO_INCREMENT,
					wikiPageTypeID TEXT,
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

				echo("wiki_pageTypes table created");

			} else {

				echo($db->mysqli->error."<br />");

			}

			$query = "

				CREATE TABLE wiki_pageTypeDefinitions (
						wikiPageTypeDefinitionID INT NOT NULL AUTO_INCREMENT,
						order INT,
						wikiPageTypeID INT,
						heading TEXT,
						description TEXT,
						contentType TEXT,
						PRIMARY KEY(wikiPageTypeDefinitionID)
					); ";

			if ($db->mysqli->query($query)) {

				echo("wiki_pageTypeDefinitions table created");

			} else {

				echo($db->mysqli->error."<br />");

			}

			$query = "

				CREATE TABLE wiki_pageContents (
						wikiPageTypeDefinitionID,
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

			if ($db->checkExists("version","module","mantisWiki")) {

				echo("version already populated<br />");

			}

			else {

				$query = "

					INSERT INTO version (
						module,
						version,
						theme
					) values (
						'mantisWiki',
						'1.0.0',
						'default'
					); ";

				if ($db->mysqli->query($query)) {

					echo("version populated<br />");

				}

			}
			
			if ($db->checkExists("adminContent","name","Blog Module")) {

				echo("adminContent already populated with the Blog Module <br />");

			}

			else {

				$query = "

					INSERT INTO adminContent (
							name,
							link,
							category
						) values (
							'Mantis Wiki Module',
							'mantisWiki/admin/mantisWikiModule.php',
							'main'
						);";

				if ($db->mysqli->query($query)) {

					echo("adminContent populated <br />");

				}

			}

			if ($db->checkExists("dContent","title","Blog")) {

				echo("dContent already populated with blog link <br />");

			}

			else {

				$query = "

					INSERT INTO dContent (
						title,
						linkName,
						directLink
					) values (
						'Mantis Wiki',
						'Mantis Wiki',
						'mantisWiki/index.php'
					);";

				if ($db->mysqli->query($query)) {

					echo("dContent populated <br />");

				}

			}

		}

	}

?>
