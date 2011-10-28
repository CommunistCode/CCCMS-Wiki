<?php 

	require_once("../../config/config.php");
	require_once($fullPath."/admin/includes/global.inc.php");
	require_once($fullPath."/admin/includes/checkLogin.inc.php");
	require_once("classes/wikiAdminTools.class.php");

	$wikiAdminTools = new wikiAdminTools();

	if (isset($_POST['createTemplate'])) {

		$i = 0;

		foreach($_POST['definition'] as $definition) {

			if ($definition['heading'] != "" AND $definition['dataType'] != "") {
			
				$definitionArray[$i] = $definition;

				$i++;
				
			}

		}

		$wikiAdminTools->createTemplate($_POST['name'],$_POST['description'],$definitionArray);

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
				
				<form action='createTemplate.php' method='post'>

					<label for="name">Name:</label>
					<input type='text' name='name' />
			
					<br /><br />
				
					<label for="descripton">Description:</label>
					<input type='text' name='description' />
					
					<br /><br />
					
					<table>
						<tr>
							<th>Heading</th>
							<th>Description</th>
							<th>Data Type</th>
						</tr>
						
						<?php

							if (!isset($_GET['numDefinitions'])) {

								$numDefinitions = 1;

							} else {

								$numDefinitions = $_GET['numDefinitions'];

							}

							for ($i=0; $i<$numDefinitions; $i++) {

								$tableRow  = "<tr>";
								$tableRow .= "<td><input type='text' name='definition[".$i."][heading]' /></td>";
								$tableRow .= "<td><input type='text' name='definition[".$i."][description]' /></td>";
								$tableRow .= "<td><input type='text' name='definition[".$i."][dataType]' /></td>";
								$tableRow .= "</tr>";

								echo($tableRow);

							}

						?>

					</table>

					<br />

					<input type='submit' value='Create Template' name='createTemplate' />

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
