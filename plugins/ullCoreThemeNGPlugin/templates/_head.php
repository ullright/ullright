<?php
  // get layout name
  $layout = sfConfig::get('app_theme', 'ullThemeDefault');
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>

<?php include_http_metas() ?>
<?php include_metas() ?>
<?php include_title() ?>

<?php $favicon_uri = sfConfig::get('app_override_favicon', '/ullCoreTheme' . sfConfig::get('app_theme_package', 'NG') . 'Plugin/images/favicon.ico') ?>
<link rel="shortcut icon" href="<?php echo $favicon_uri ?>" type="image/vnd.microsoft.icon" />

<?php
  $path =  '/ullCoreTheme' . sfConfig::get('app_theme_package', 'NG') . "Plugin/css/main.css";
  sfContext::getInstance()->getResponse()->addStylesheet($path, 'first', array('media' => 'all'));
?>

<!-- html head slot -->
<?php include_slot('html_head') ?>
<!-- end of html head slot -->

<?php use_javascript('/ullCorePlugin/js/jq/jquery-min.js', 'first') ?>
<?php use_javascript('/ullCorePlugin/js/jq/jquery-ui-min.js', 'first') ?>
<?php use_javascript('/ullCorePlugin/js/jq/jquery.add_select_filter.js') ?>
<?php use_javascript('/ullCorePlugin/js/jq/jquery.replace_time_duration_select.js') ?>
<?php use_javascript('/ullCorePlugin/js/miscellaneous.js') ?>
<?php use_javascript('/ullCorePlugin/js/sidebar.js') ?>
<?php use_javascript('/ullCorePlugin/js/colorpicker.js') ?>
<?php use_javascript('/ullCorePlugin/js/star-rating/jquery.rating.pack.js') ?>
<?php use_javascript('/ullCorePlugin/js/star-rating/jquery.MetaData.js') ?>

<?php use_stylesheet('/ullCorePlugin/css/jqui/ui.all.css', 'last', array('media' => 'all')) ?>
<?php use_stylesheet('/ullCorePlugin/css/colorpicker.css', 'last', array('media' => 'all')) ?>
<?php use_stylesheet('/ullCorePlugin/css/star-rating/star-rating.css', 'last', array('media' => 'all')) ?>

<?php if ($overrideCss = sfConfig::get('app_override_css')): ?>
  <?php sfContext::getInstance()->getResponse()->addStylesheet($overrideCss, 'last', array('media' => 'all')) ?>
<?php endif ?>
</head>

