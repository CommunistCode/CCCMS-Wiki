<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<title><?php echo($title); ?> : Blog</title>
		<link href="../themes/<?php echo($pageTools->getTheme("base")); ?>/stylesheets/base.css" rel="stylesheet" type="text/css" />
		<link href="themes/<?php echo($pageTools->getTheme("blog")); ?>/stylesheets/blogStyle.css" rel="stylesheet" type"text/css" />
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

				<div class='blogLinks'>

					<?php
						require_once($fullPath."/blog/includes/blogLinks.inc.php");
					?>

				</div>
	
				<div class="blogBody">
		
					<h1>Blog Posts</h1>	

					<?php 
	
						echo($content);					

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
