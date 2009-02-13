<?php slot('html_head') ?>
  <?php echo javascript_include_tag('/ullCorePlugin/js/jq/jquery-min.js') ?>
  <?php echo javascript_include_tag('/ullCorePlugin/js/jq/jquery-ui-min.js') ?>
<?php end_slot() ?>

<?php use_stylesheet('/ullCorePlugin/css/jqui/ui.all.css', 'last', array('media' => 'all')) ?>