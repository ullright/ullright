<?php // Switch between inline editing view and visitor view ?>

<?php use_stylesheet('/ullCmsThemeNGPlugin/css/inline_editing.css', 'first') ?>

<?php if (UllUserTable::hasPermission('ull_cms_edit')): ?>
  <div id="ull_cms_switch">
    <?php echo link_to_function(
      ull_image_tag('edit'), 
      'switchEditOn()',
      array('id' => 'ull_cms_switch_on')
    ) ?>
    <?php echo link_to_function(
      ull_image_tag('edit'), 
      'switchEditOff()',
      array('id' => 'ull_cms_switch_off')
    ) ?>    
  </div>
<?php endif ?>

<?php $inline_editing = $sf_user->getAttribute('inline_editing') ?>

<?php if ($inline_editing): ?>
  <?php echo javascript_tag('
    $(document).ready(function() {
      $("#ull_cms_switch_on").hide();
    });
  ') ?>
<?php else: ?>
  <?php echo javascript_tag('
    $(document).ready(function() {
      $("#ull_cms_switch_off").hide();
      $(".inline_editing").hide();
    });
  ') ?>
<?php endif  ?>
    
<?php echo javascript_tag('    
  function switchEditOn() {
    $(".inline_editing").show();
    $("#ull_cms_switch_off").show();
    $("#ull_cms_switch_on").hide();
    $.ajax({
      url: "' . url_for('ullCms/toggleInlineEditing?inline_editing=1') . '",
    });        
  }
    
  function switchEditOff() {
    $(".inline_editing").hide();
    $("#ull_cms_switch_off").hide();
    $("#ull_cms_switch_on").show();   
    $.ajax({
      url: "' . url_for('ullCms/toggleInlineEditing?inline_editing=0') . '",
    });        
  }    
') ?>