<?php 

/**
 * Replace this with your custom layout
 * 
 * Example layouts can be found in plugins/ullCoreThemeNGPlugin/templates/
 */

// Switch between webapp and website example layouts
$layout =  $sf_user->getAttribute('layout');
$layout = ($layout) ? $layout : 'layout_webapp';


// Link to the default layout
// To customize, copy it here from plugins/ullCoreThemeNGPlugin/templates/ and modify it
$path = sfConfig::get('sf_plugins_dir') . 
  '/ullCoreTheme' .
  sfConfig::get('app_theme_package', 'NG') .
  'Plugin/templates/' . $layout . '.mobile.php'
;

require($path);

