<?php // Switch between inline editing view and visitor view ?>

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

<?php echo javascript_tag('

  $("#ull_cms_switch_off").show();
  $("#ull_cms_switch_on").hide();
   
  function switchEditOn() {
    $(".inline_editing").show();
    $("#ull_cms_switch_off").show();
    $("#ull_cms_switch_on").hide();    
  }
    
  function switchEditOff() {
    $(".inline_editing").hide();
    $("#ull_cms_switch_off").hide();
    $("#ull_cms_switch_on").show();    
  }    
    
') ?>