<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN" "http://www.wapforum.org/DTD/xhtml-mobile10.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>

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
<link rel="shortcut icon" href="<?php echo $favicon_uri ?>" type="image/vnd.microsoft.icon" />

<?php /* Main default ullright stylesheet */ ?>
<?php use_stylesheet('/ullCoreTheme' . sfConfig::get('app_theme_package', 'NG') . 
  'Plugin/css/main.mobile.css', 'first', array('media' => 'all')) ?>

<?php /* Default custom stylesheet */ ?>
<?php /* Usually located in  web/css/custom_override.css */ ?>
<?php if ($overrideCss = sfConfig::get('app_override_css')): ?>
  <?php $overrideCss = str_replace('.css', '.mobile.css', $overrideCss)?>
  <?php sfContext::getInstance()->getResponse()->addStylesheet($overrideCss, 'last', array('media' => 'all')) ?>
<?php endif ?>  

<?php /* Default ullright javascripts */ ?> 
<?php use_javascript('/ullCorePlugin/js/jq/jquery-min.js') ?>  
<?php use_javascript('/ullCorePlugin/js/miscellaneous.js') ?>

<?php /* Include content of the  html_head slot */ ?>
<?php include_slot('html_head') ?>

</head>

