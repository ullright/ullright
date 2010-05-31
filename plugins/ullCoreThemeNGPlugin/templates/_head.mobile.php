<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN" "http://www.wapforum.org/DTD/xhtml-mobile10.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>

<?php include_http_metas() ?>
<?php include_metas() ?>
<?php include_title() ?>

<?php $favicon_uri = sfConfig::get('app_override_favicon', '/ullCoreTheme' . sfConfig::get('app_theme_package', 'NG') . 'Plugin/images/favicon.ico') ?>
<link rel="shortcut icon" href="<?php echo $favicon_uri ?>" type="image/vnd.microsoft.icon" />

<?php use_stylesheet('/ullCoreTheme' . sfConfig::get('app_theme_package', 'NG') . 
  'Plugin/css/main.mobile.css', 'first', array('media' => 'all')) ?>

<!-- html head slot -->
<?php include_slot('html_head') ?>
<!-- end of html head slot -->


<?php if ($overrideCss = sfConfig::get('app_override_css')): ?>
  <?php sfContext::getInstance()->getResponse()->addStylesheet($overrideCss, 'last', array('media' => 'all')) ?>
<?php endif ?>
</head>

