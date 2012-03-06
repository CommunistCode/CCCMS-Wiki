<?php

  $folderArray = explode("/",__DIR__);
  $moduleDirectoryIndex = count($folderArray)-2;
  DEFINE('MODULE',$folderArray[$moduleDirectoryIndex]);

  $theme = $pageTools->getTheme(MODULE);
  DEFINE('THEME',$theme);

  // Give the membership module name
  DEFINE('MEMBER_MODULE_DIR',"membership");

?>
