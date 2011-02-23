<?php 

// Link to the default layout
// To customize, copy it here from plugins/ullCoreThemeNGPlugin/templates/ and modify it
$path = sfConfig::get('sf_plugins_dir') . 
  '/ullCoreTheme' .
  sfConfig::get('app_theme_package', 'NG') .
  'Plugin/templates/layout.php'
;

require($path);