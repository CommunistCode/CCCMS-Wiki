<?php

	$wikiTools = new wikiTools();

?>

<form action='' method='post'>

	<label for='pageTitle'>Page Title:</label>
	<input type='text' name='pageTitle'>

	<br /><br />

	<label for='pageTemplate'>Page Template:</label>
	<select name='pageTemplate'>

		<option>List Page Templates Here</option>

	</select>

	<br /><br />

	<label for='pageCategory'>Page Category:</label>
	<select name='pageCategory'>

		<?php

			$wikiTools->renderCategorySelectOptions();

		?>

	</select>

	<br /><br />

	<input type='submit' value='Create Page' />

</form>
