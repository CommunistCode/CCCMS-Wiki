<form action='deleteTemplate.php' method='post'>

  <select name='templateID'>

    <?php
  
      $templateArray = $_wikiTools->getAllTemplates();

      foreach($templateArray as $template) {

        echo("<option value='".$template['wikiTemplateID']."'>".$template['name']."</option>");

      }

    ?>

  </select>

  <input type='submit' value='Delete Template' name='deleteTemplate' />

</form>
