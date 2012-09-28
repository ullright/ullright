<?php $element_data = $sf_data->getRaw('element_data') ?>
<?php $values = $element_data['values'] ?>
<?php $element_id = $element_data['id'] ?>
<?php $element_type = $element_data['type'] ?>
<?php $do_render_html = (isset($do_render_html)) ? $do_render_html : true ?>

<?php // Wrap the element's html in a container to allow easy jquery selection ?>
<div class="content_element_html_container content_element_html_container_<?php echo $element_type ?>"
  id="content_element_html_container_<?php echo $element_id ?>">
  
  <div class="content_element_html content_element_html_<?php echo $element_type ?>"
    id="content_element_html_<?php echo $element_id ?>">
    
    <?php if ($do_render_html): // do not render for "add" action ?>
      <?php $partial_name = 'ullTableTool/' . 'contentElement' . 
        sfInflector::classify($element_type) ?>
      
      <?php include_partial($partial_name, array(
        'type'       => $element_type,
        'element_id' => $element_id,
        'values'     => $element_data['values'],
      )) ?>
      
      <?php $json = htmlentities(json_encode($element_data)) ?>
      
      <?php echo input_hidden_tag(
        'content_element_data_' . $element_id,
        $json,
        array('id' => 'content_element_data_' . $element_id)    
      ) ?>
    <?php endif ?>
    
  </div>  
  
</div>