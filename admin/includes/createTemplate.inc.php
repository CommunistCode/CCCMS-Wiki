<form action='createTemplate.php' method='post'>

  <label for="name">Name:</label>
  <input type='text' name='name' />

  <br /><br />

  <label for="descripton">Description:</label>
  <input type='text' name='description' />

  <br /><br />

  <table id="defTable">
    <tr>
      <th>Heading</th>
      <th>Description</th>
      <th>Data Type</th>
      <th></th>
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

  <script src='scripts/wikiAdmin.script.js' type='text/javascript'></script>

  <input type='submit' value='Create Template' name='createTemplate' />
  <input type='button' value='Add Extra Row' onclick="addDefinitionRow()" />

</form>
