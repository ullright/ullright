<?php $element_data = $sf_data->getRaw('element_data') ?>

<div class="content_element_html_and_controls 
  content_element_html_and_controls_<?php echo $element_data['type'] ?>"
  id="content_element_html_and_controls_<?php echo $element_data['id'] ?>">
  
  <?php include_partial('ullTableTool/ullContentElementControlsEdit', array(
    'element_data'    => $element_data,
    'field_id'        => $field_id,    
  )) ?>

  <?php include_partial('ullTableTool/ullContentElementHtml', array(
    'element_data'    => $element_data,
    'field_id'        => $field_id,    
  )) ?>
  
  <?php include_partial('ullTableTool/ullContentElementControlsAdd', array(
    'element_data'    => $element_data,
    'element_types'   => $element_types,
    'field_id'        => $field_id,    
  )) ?>    
  
</div>  
