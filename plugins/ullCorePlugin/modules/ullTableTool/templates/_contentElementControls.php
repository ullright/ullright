<div class="content_element_controls" 
  id="content_element_controls_<?php echo $element_id ?>" >

  <div class="content_element_control">
    <?php echo link_to_function(
      ull_image_tag('edit'),
      'contentElementEdit("' . $element_id . '")'
    ) ?>  
  </div>
  
  <div class="content_element_control">
    <?php echo ull_image_tag('delete') ?>
  </div>  
  
</div>


