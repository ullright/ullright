<div class="content_element_controls" 
  id="content_element_controls_<?php echo $element_id ?>" >

  <div class="content_element_control">
    <?php echo link_to_function(
      ull_image_tag('edit'),
      'contentElementEdit("' . $element_id . '")'
    ) ?>  
  </div>
  
  <div class="content_element_control">
    <?php echo link_to_function(
      ull_image_tag('delete'),
      'contentElementDelete(' .
        '"' . $element_id . '", ' .
        '"' . $field_id . '" ' .
      ')',
      'confirm=' . __('Are you sure?', null, 'common')        
    ) ?>
  </div>  
  
  <div class="content_element_control">
    <?php echo link_to_function(
      '&uArr;',
      'contentElementMove(' .
        '"' . $element_id . '", ' .
        '"' . $field_id . '", ' .
        '"up"' .
      ')'
    ) ?>
  </div>  
  
  <div class="content_element_control">
    <?php echo link_to_function(
      '&dArr;',
      'contentElementMove(' .
        '"' . $element_id . '", ' .
        '"' . $field_id . '", ' .
        '"down"' .
      ')'
    ) ?>
  </div>    

  
</div>


