<?php

	$wikiTools = new wikiTools();

	$templateArray = $wikiTools->getAllTemplates();

?>

<form action='wikiPage.php' method='post'>

	<label for='pageTitle'>Page Title:</label>
	<input type='text' name='pageTitle'>

	<br /><br />

	<label for='pageTemplate'>Page Template:</label>
	<select name='pageTemplate'>

		<?php

			foreach($templateArray as $template) {

				echo("<option value='".$template['wikiTemplateID']."'>".$template['name']."</option>");

			}

		?>

	</select>

	<br /><br />

	<label for='pageCategory'>Page Category:</label>
	<select name='pageCategory'>

		<?php

			$wikiTools->renderCategorySelectOptions();

		?>

	</select>

	<br /><br />

	<input type='submit' value='Create Page' name='doCreatePage' />

</form>
