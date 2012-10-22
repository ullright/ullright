<!DOCTYPE html>
<html lang="<?php echo $sf_user->getCulture() ?>">

<!--  Begin of html head -->
<head>

<?php /* Load ullright html head statements*/ ?>
<?php /* Located in plugins/ullCorePlugin/modules/default/templates/_ullright_html_head.php */ ?>
<?php include_partial('default/ullright_html_head')?>


<!--  Begin of custom html head statements -->

<?php /* Do not use a <title> tag, as this is handled by symfony */ ?>

<!-- custom meta information -->
<meta name="author" content="Klemens Ullmann-Marx and the ull.at team" />
<meta name="description" content="ullright is an opensource platform for websites & applications initiated by ull.at" />
<meta name="keywords" content="ullright, ullCms, ullUser, ullNewsletter, website, CMS, content management system, email newsletter, framework, ull.at" />

<meta charset="utf-8" />
<meta name="robots" content="index,follow" />
<meta name="generator" content="ullright">

<!-- Favicon -->
<?php /* located in web/images/favicon.ico */ ?>
<link rel="shortcut icon" href="/images/favicon.ico" />

<!-- ullNews RSS feed -->
<?php include_component('ullNews', 'rssFeed') ?>


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
<link href='http://fonts.googleapis.com/css?family=Metrophobic' rel='stylesheet' type='text/css' />

</head>
<!--  End of html head -->
