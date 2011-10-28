<?php 

	require_once("../../config/config.php");
	require_once($fullPath."/admin/includes/global.inc.php");
	require_once($fullPath."/admin/includes/checkLogin.inc.php");
	require_once("classes/wikiAdminTools.class.php");

	$wikiAdminTools = new wikiAdminTools();

	if (isset($_POST['deleteTemplate'])) {

		$wikiAdminTools->deleteTemplate($_POST['templateID']);

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
					Create Wiki Template
				</h1>
				
				<br />
				
				<form action='deleteTemplate.php' method='post'>


					<select name='templateID'>
						
						<?php

							$templateArray = $wikiAdminTools->getAllTemplates();

							foreach($templateArray as $template) {

								echo("<option value='".$template['wikiTemplateID']."'>".$template['name']."</option>");

							}

						?>

						</select>

					<input type='submit' value='Delete Template' name='deleteTemplate' />

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
