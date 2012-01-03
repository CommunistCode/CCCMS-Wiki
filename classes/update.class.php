<?php

	require_once($fullPath . "/classes/dbConn.class.php");
	require_once($fullPath . "/classes/versionTools.class.php");

	class updateWiki {

    var $module = "wiki";
    var $latestVersion = "1.2.0";

		function update() {

			$db = new dbConn();
			$vt = new versionTools();


			if (!$vt->isVersionGreater($this->module,$this->latestVersion)) {

				echo("Already up-to-date at version ".$this->latestVersion."<br />");

			} else {

				echo("Current version is ".$vt->getVersion($this->module)." - updating! <br />");

			}

			if ($this->update_1_2_0($db,$vt) == 1) {

				echo("All updates were sucessful!<br />");

			} else {

				echo("Not all updates were successful<br />");

			}

		}

    /*************************************************************************
    * Update 1.2.0
    * ------------------------------------------------------------------------
    * Move all the category ids into a new table, to keep 1nf database format
    * and allow wiki pages to be present in multiple categories 
    **************************************************************************/
    private function update_1_2_0($db,$vt) {

      $version = "1.2.0";

      if (!$vt->isVersionGreater($this->module,$version)) {

        return 1;

      } else {

        if ($this->update_1_1_0($db,$vt) == -1) {

            return -1;

        }

      }

      $error = 0;

      // Create new pageCategoryTable

      $result = $db->mysqli->query("CREATE TABLE wiki_pageCategories(wikiPageID int,
                                                                     wikiCategoryID int,
                                                                     PRIMARY KEY(wikiPageID,wikiCategoryID))
                                   ");
      if ($result) {

        echo("wiki_pageCategories table created<br />");

      } else {

        $error = 1;

      }

      // Get all current pages and categories to transfer to new table

      $result = $db->select("wikiPageID,wikiCategoryID","wiki_pages");

      $pageCategoryArray = array();

      for ($i=0; $i<$result->num_rows; $i++) {
        
        $row = $result->fetch_assoc();

        $insertResult = $db->insert("wiki_pageCategories",
                                    "wikiPageID,wikiCategoryID",
                                    $row['wikiPageID'].",".$row['wikiCategoryID']);

        if (!$insertResult) {

          $error = 1;

        }

      }

      // Finally remove the pageCategoryID table

      $result = $db->mysqli->query("ALTER TABLE wiki_pages DROP COLUMN wikiCategoryID");

      if (!$result) {

        $error = 1;

      }

  		if (!$error) {

				if ($vt->updateVersion($this->module,$version)) {

					echo("<strong>Updated to ".$version."</strong><br />");
					return 1;

				} else {

					echo("<strong>Update to ".$version." complete but could not update version table!</strong><br />");
          return -1;

				}

			} else {

				echo("<strong>Update to version ".$version." failed in some areas!</strong><br />");
        return -1;

			}
    
    }

		private function update_1_1_0($db,$vt) {

      $version = "1.1.0";

			if (!$vt->isVersionGreater($this->module,$version)) {
				
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

				if ($db->updateVersion($module,$version)) {

					echo("<strong>Updated to ".$version."</strong><br />");
					return 1;

				} else {

					echo("<strong>Updated to ".$version." complete but could not update version table!</strong><br />");
          return -1;

				}

			} else {

				echo("<strong>Update to version ".$version." failed in some areas!</strong><br />");
        return -1;

			}

		}

	}

?>
