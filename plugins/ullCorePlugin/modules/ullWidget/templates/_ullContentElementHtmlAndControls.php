<?php $element_data = $sf_data->getRaw('element_data') ?>
<?php $element_types = $sf_data->getRaw('element_types') ?>
<?php $do_render_html = (isset($do_render_html)) ? $do_render_html : true ?>
<?php $css_class = (isset($css_class)) ? $css_class : null ?>

<div class="content_element_html_and_controls 
  content_element_html_and_controls_<?php echo $element_data['type'] ?>"
  id="content_element_html_and_controls_<?php echo $element_data['id'] ?>">
  
  <?php include_partial('ullWidget/ullContentElementControlsEdit', array(
    'element_data'    => $element_data,
    'field_id'        => $field_id,    
  )) ?>

  <?php include_partial('ullWidget/ullContentElementHtml', array(
    'element_data'    => $element_data,
    'field_id'        => $field_id,   
    'do_render_html'  => $do_render_html 
  )) ?>
  
  <?php include_partial('ullWidget/ullContentElementControlsAdd', array(
    'element_data'    => $element_data,
    'element_types'   => $element_types,
    'field_id'        => $field_id,    
    'css_class'       => $css_class,
  )) ?>    
  
</div>  
