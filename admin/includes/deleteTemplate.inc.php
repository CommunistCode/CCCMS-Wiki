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
