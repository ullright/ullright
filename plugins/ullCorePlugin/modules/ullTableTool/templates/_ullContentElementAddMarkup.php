<div class="content_element_markup" id="content_element_markup_<?php echo $element_id ?>">
  
  <?php include_partial('ullTableTool/contentElementControls', array(
    'element'    => $element,
    'element_id' => $element_id,
    'field_id'   => $field_id,
  )) ?>
  
  <?php include_partial('ullTableTool/contentElementHtmlEmpty', array(
    'element'    => $element,
    'element_id' => $element_id,
  )) ?>  
    
  <?php include_partial('ullTableTool/contentElementAdd', array(
    'element'    => $element,
    'element_id' => $element_id,
    'elements'   => $elements,
    'field_id'   => $field_id,
  )) ?>    
    
  <?php include_partial('ullTableTool/contentElementForm', array(
    'element'    => $element,
    'element_id' => $element_id,
    'field_id'   => $field_id,
    'generator'  => $generator,
  )) ?>    
    
</div>


    
