<?php require_once(sfConfig::get('sf_plugins_dir') . 
  '/ullCorePlugin/modules/ullTableTool/templates/editSuccess.php') ?>
  
<?php echo javascript_tag('

$(document).ready(function() {
  $("#fields_end_date").parent().append(\'' . link_to_function(
      __('Use start date', null, 'ullCourseMessages'),
      'useStartDate()',
      array('id' => 'use_start_date_link')
    ) . '\');
    
 
});

function useStartDate() {
  $("#fields_end_date").val($("#fields_begin_date").val());
  $("#fields_number_of_units").val(1);
}

') ?>  
