<?php 

/**
 * Replace this entire file with your custom layout
 * 
 * Example layouts can be found in plugins/ullCoreThemeNGPlugin/templates/
 */


// Switch between webapp and website example layouts
$layout =  $sf_user->getAttribute('layout');
$layout = ($layout) ? $layout : 'layout_webapp';

use_stylesheet('/ullCoreTheme' . sfConfig::get('app_theme_package', 'NG') . 
  'Plugin/css/' . $layout . '.css', 'last', array('media' => 'all'));

// Link to the default layout
// To customize, copy it here from plugins/ullCoreThemeNGPlugin/templates/ and modify it
$path = sfConfig::get('sf_plugins_dir') . 
  '/ullCoreTheme' .
  sfConfig::get('app_theme_package', 'NG') .
  'Plugin/templates/' . $layout . '.php'
;

require($path);