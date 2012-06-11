<?php 
/**
 * ullright default html head statements
 */
?>

<!-- Html meta information -->
<?php // Defined in /apps/frontend/config/view.yml ?>
<?php include_http_metas() ?>
<?php include_metas() ?>
<?php include_title() ?>

<!-- Favicon -->
<?php // Path defined in /apps/frontend/config/app.yml ?>
<?php $favicon_uri = sfConfig::get('app_override_favicon', 
  '/ullCoreTheme' . 
  sfConfig::get('app_theme_package', 'NG') . 
  'Plugin/images/favicon.ico') ?>
<link rel="shortcut icon" href="<?php echo $favicon_uri ?>" />

<?php // Load default ullright stylesheet ?> 
<?php use_stylesheet('/ullCoreTheme' . sfConfig::get('app_theme_package', 'NG') . 
  'Plugin/css/main.css', 'first', array('media' => 'all')) ?>
  
<?php // Load default ullright javascripts ?>  
<?php use_javascript('http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js', 'first') ?>
<?php use_javascript('/ullCorePlugin/js/miscellaneous.js') ?>

