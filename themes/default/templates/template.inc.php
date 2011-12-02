<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,400italic,600' rel='stylesheet' type='text/css'>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<title><?php echo($title .":". $pageTitle); ?></title>
		<link href="../themes/<?php echo($pageTools->getTheme("base")); ?>/stylesheets/base.css" rel="stylesheet" type="text/css" />
		<link href="themes/<?php echo($pageTools->getTheme("wiki")); ?>/stylesheets/wikiStyle.css" rel="stylesheet" type="text/css" />
	</head>
	<body>
		<div id="mainContainer">
			<div id="title">
				<?php 
					require_once($fullPath."/themes/".$pageTools->getTheme("base")."/templates/title.inc.php"); 
				?>
			</div>
			<div id='navBar'>
				<?php 
					require_once($fullPath."/themes/".$pageTools->getTheme("base")."/templates/links.inc.php"); 
				?>
			</div>
			<div id='bodyContainer'>

				<div class="wikiLinks">
					<?php
						require_once("includes/wikiLinks.inc.php");
					?>
				</div>
			
				<div class="wikiBody">
		
			  <?php

					echo("<div class='pageHeading'>");
					echo("<h1>".$heading."</h1>");
					echo("</div>");
					
					if (!isset($disableWikiCategoryBar)) {
						
						require_once("includes/wikiCategoryBar.inc.php");

					}

					if (isset($help)) {
						
						echo("<div class='help'>".$help."</div>");

					}

					if (isset($content)) {

						echo($content);

					}

          if (isset($include)) {

						include($include);

					}

        ?>

				
				</div>

			</div>

			<div id="footer">
				<?php 
					require_once($fullPath."/themes/".$pageTools->getTheme("base")."/templates/footer.inc.php"); 
				?>
			</div>
		</div>
	</body>
</html>



