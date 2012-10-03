<?php $element_types = $sf_data->getRaw('element_types') ?>
<?php $element_data = $sf_data->getRaw('element_data') ?>
<?php $do_render_html = (isset($do_render_html)) ? $do_render_html : true ?>
<?php $do_render_form = (isset($do_render_form)) ? $do_render_form : true ?>
<?php $css_class = (isset($css_class)) ? $css_class : null ?>

<div class="content_element <?php echo $css_class ?>" 
  id="content_element_<?php echo $element_data['id'] ?>">

  <?php include_partial('ullWidget/ullContentElementHtmlAndControls', array(
    'element_data'    => $element_data,
    'element_types'   => $element_types,
    'field_id'        => $field_id,
    'do_render_html'  => $do_render_html 
  )) ?>
  
  <?php include_component('ullWidget', 'ullContentElementForm', array(
    'element_data'    => $element_data,
    'element_types'   => $element_types,
    'field_id'        => $field_id,
    'do_render_form'  => $do_render_form,      
  )) ?>      

</div>
