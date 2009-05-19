<?php slot('html_head') ?>
  <?php echo javascript_include_tag('/ullCorePlugin/js/jq/jquery-min.js') ?>
  <?php echo javascript_include_tag('/ullCorePlugin/js/jq/jquery-ui-min.js') ?>
  <?php //echo javascript_include_tag('/ullCorePlugin/js/jq/jquery.flexbox.js') ?>
  <?php //echo javascript_include_tag('/ullCorePlugin/js/sexy_combo/jquery.sexy-combo-2.0.6.js') ?>
  <?php //echo javascript_include_tag('/ullCorePlugin/js/quickselect/quicksilver.js') ?>
  <?php //echo javascript_include_tag('/ullCorePlugin/js/quickselect/jquery.quickselect.js') ?>
  <?php //echo javascript_include_tag('/ullCorePlugin/js/extjs/ext-base.js') ?>
  <?php //echo javascript_include_tag('/ullCorePlugin/js/extjs/ext-all-debug.js') ?>
  
  <?php /* echo javascript_tag("
        // Path to the blank image should point to a valid location on your server
        Ext.BLANK_IMAGE_URL = '/ullCorePlugin/js/extjs/images/default/s.gif';
 
        Ext.onReady(function(){
 
           console.info('woohoo!!!');
 
        }); //end onReady  
  ") */?>
  
<?php end_slot() ?>

<?php use_stylesheet('/ullCorePlugin/css/jqui/ui.all.css', 'last', array('media' => 'all')) ?>
<?php //use_stylesheet('/ullCorePlugin/css/jqui/jquery.flexbox.css', 'last', array('media' => 'all')) ?>
<?php //use_stylesheet('/ullCorePlugin/css/sexy_combo/sexy-combo.css', 'last', array('media' => 'all')) ?>
<?php //use_stylesheet('/ullCorePlugin/css/sexy_combo/sexy.css', 'last', array('media' => 'all')) ?>
<?php //use_stylesheet('/ullCorePlugin/css/quickselect/jquery.quickselect.css', 'last', array('media' => 'all')) ?>
<?php //use_stylesheet('/ullCorePlugin/js/extjs/css/ext-all.css', 'first', array('media' => 'all')) ?>