<div class="content_element content_element_<?php echo $element ?>"
  id="content_element_<?php echo $id ?>">
  
  <?php $values = $sf_data->getRaw('values') ?>
  
  <?php $partial_name = 'ullTableTool/' . 'contentElement' . 
      sfInflector::classify($element) ?>
  
  <?php include_partial($partial_name, array(
    'element'  => $element,
    'id'       => $id,
    'values'   => $values,
  )) ?>
  
</div>  
