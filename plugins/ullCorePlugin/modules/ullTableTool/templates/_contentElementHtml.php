<?php $values = $sf_data->getRaw('values') ?>

<div class="content_element content_element_<?php echo $element ?>"
  id="content_element_<?php echo $element_id ?>">
  
  <?php $partial_name = 'ullTableTool/' . 'contentElement' . 
    sfInflector::classify($element) ?>
  
  <?php include_partial($partial_name, array(
    'element'    => $element,
    'element_id' => $element_id,
    'values'     => $values,
  )) ?>
  
  <?php $data = array(
    'element'     => $element,
    'element_id'  => $element_id,
    'values'      => $values,
  ) ?>
  
  <?php $json = htmlentities(json_encode($data)) ?>
  
  <?php echo input_hidden_tag(
    'content_element_data_' . $element_id,
    $json,
    array('id' => 'content_element_data_' . $element_id)    
  ) ?>
  
</div>  
