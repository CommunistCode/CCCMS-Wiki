<?php 

	require_once("../../config/config.php");
	require_once($fullPath."/admin/includes/global.inc.php");
	require_once($fullPath."/admin/includes/checkLogin.inc.php");
	require_once("classes/wikiAdminTools.class.php");

	$wikiAdminTools = new wikiAdminTools();

	if (isset($_POST['addCategory'])) {

		//Add new category

	}

	if (isset($_POST['deleteCategory'])) {

		//Delete category

	}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<title>Admin Area - Mantis Wiki Module</title>
		<link href="<?php echo($directoryPath); ?>/admin/stylesheet/stylesheet.css" rel="stylesheet" type="text/css" />
	</head>
	
	<body>
		
		<div id="mainContainer">
			
			<div id="title">
			
				<?php 
					require_once($fullPath . "/admin/includes/title.inc.php");
				?>
			
			</div>
			
			<div id="body">
				
				<h1>
					Manage  Wiki Categories
				</h1>
				
				<br />
				
				<form action='createTemplate.php' method='post'>

					<label for="name">Category Name:</label>
					<input type='text' name='name' />	

					<input type='submit' value='Add Category' name='addCategory' />

				</form>

				<form>

					<label for="name">Category Name:</label>
					
					<select name='name'>

						<?php

							//Get all categories

						?>

					</select>

					<input type='submit' value='Delete Category' name='deleteCategory' />

				</form>

			</div>
			
			<div id="links">
				<?php 

					$page=0;

					require_once($fullPath . "/admin/includes/adminLinks.inc.php"); 

				?>
			</div>
			<div id="footer">
				<?php 
					require_once($fullPath . "/admin/includes/footer.inc.php"); 
				?>
			</div>
		</div>
	</body>
</html>
