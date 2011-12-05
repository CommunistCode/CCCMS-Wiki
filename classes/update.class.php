<?php

	require_once($fullPath . "/classes/dbConn.class.php");
	require_once($fullPath . "/classes/versionTools.class.php");

	class updateWiki {

		function update() {

			$db = new dbConn();
			$vt = new versionTools();

			$module = "wiki";
			$latestVersion = "1.1.0";

			if (!$vt->isVersionGreater($module,$latestVersion)) {

				echo("Already up-to-date at version ".$latestVersion."<br />");

			} else {

				echo("Current version is ".$vt->getVersion($module)." - updating! <br />");

			}

			if ($this->update_1_1_0($db,$vt) == 1) {

				echo("All updates were sucessful!<br />");

			} else {

				echo("Not all updates were successful<br />");

			}

		}

		private function update_1_1_0($db,$vt) {

			if (!$vt->isVersionGreater("wiki","1.1.0")) {
				
				return 1;

			}

			$error = 0;

			$query = "CREATE TABLE wiki_pageImages ( imageID int AUTO_INCREMENT,
																							 wikiPageID int,
																							 wikiTemplateDefinitionID int,
																							 type text,
																							 caption text,
																							 memberID int,
																							 date int,
																							 PRIMARY KEY(imageID))";

			if ($db->mysqli->query($query)) {

				echo("wiki_pageImages table created!<br />");

			} else {

				$error = 1;

			}

			if (!$error) {

				if ($db->updateVersion("wiki","1.1.0")) {

					echo("<strong>Updated to 1.1.0</strong><br />");
					return 1;

				} else {

					echo("<strong>Updated to 1.1.0 complete but could not update version table!</strong><br />");

				}

			} else {

				echo("<strong>Update to version 1.1.0 failed in some areas!</strong><br />");

			}

		}

	}

?>
