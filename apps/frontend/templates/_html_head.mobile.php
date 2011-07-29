<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN" "http://www.wapforum.org/DTD/xhtml-mobile10.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<!--  Begin of html head -->
<head>

<?php /* Load default ullright html head */ ?>
<?php include_partial('default/html_head')?>


<?php /* Add custom html head statements here... */ ?>

<!-- ullNews RSS feed -->
<?php include_component('ullNews', 'rssFeed') ?>

<?php /* Add example website stylesheet */ ?>
<?php /* use_stylesheet('/ullCoreTheme' . sfConfig::get('app_theme_package', 'NG') . 
  'Plugin/css/layout_website.mobile.css', 'last', array('media' => 'all')) */?>

<?php /* Add the ullright webapp stylesheet */ ?>
<?php /* use_stylesheet('/ullCoreTheme' . sfConfig::get('app_theme_package', 'NG') . 
  'Plugin/css/layout_webapp.mobile.css', 'last', array('media' => 'all')) */?>
  
<?php /* Add custom stylesheet */ ?>
<?php /* Located in web/css/ */ ?>
<?php use_stylesheet('custom.mobile.css', 'last', array('media' => 'all')) ?>      

<?php /* Add global custom javascripts*/?>
<?php /* Put files in web/js/ */?>
<?php // use_javascript('/js/my_javascript.js') ?>

<?php /* Add other html head statements here like webfonts, stats, ... */?>

</head>
<!--  End of html head -->
