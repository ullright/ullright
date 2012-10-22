<!-- Start of generic ullright html head -->

<!-- Meta information from symfony includes / view.yml  -->
<?php // Defined in /apps/frontend/config/view.yml ?>
<?php include_http_metas() ?>
<?php include_metas() ?>
<?php include_title() ?>

<?php // Deprecated favicon from definition in /apps/frontend/config/app.yml ?>
<?php if ($favicon = sfConfig::get('app_override_favicon')) : ?> 
  <link rel="shortcut icon" href="<?php echo $favicon ?>" />
<?php endif ?>

<?php // Load default ullright stylesheet ?> 
<?php $file = ('mobile' === $sf_request->getRequestFormat()) ? 
  'main.mobile.css' : 'main.css' ?>
<?php use_stylesheet('/ullCoreTheme' . sfConfig::get('app_theme_package', 'NG') . 
  'Plugin/css/' . $file, 'first', array('media' => 'all')) ?>
  
<?php // Load default ullright javascripts ?>  
<?php use_javascript('/ullCorePlugin/js/jq/jquery-min.js', 'first') ?>
<?php use_javascript('/ullCorePlugin/js/miscellaneous.js') ?>

<?php // Add html head statements from anywhere using symfony slots ?>
<?php if (has_slot('html_head')): ?>
  <?php include_slot('html_head') ?>
<?php endif ?>

<!-- End of generic ullright html head -->