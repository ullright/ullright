<?php 

// Use default theme layout.
// To customize, define your own layout here instead 
$path = sfConfig::get('sf_plugins_dir') . 
  '/ullCoreTheme' .
  sfConfig::get('app_theme_package', 'NG') .
  'Plugin/templates/layout.php'
;

require($path);