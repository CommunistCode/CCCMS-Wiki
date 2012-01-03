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
  
  <table id='categoryTable'>

  	<tr>
      <th>Page Category:</th>
      <th></th>
    </tr>
    <tr>
      <td><select id='categoryList' name='pageCategory[]'> <?php $wikiTools->renderCategorySelectOptions();	?> </select></td>
      <td></td>
    </tr>  
  </table>

  <br />

  <input class='highlightHover' type='button' value='Add Another Category' onclick="addCategoryRow()" />
	<input class='highlightHover' type='submit' value='Create Page' name='doCreatePage' />

</form>
