<?php $element_types = $sf_data->getRaw('element_types') ?>
<?php $element_data = $sf_data->getRaw('element_data') ?>
<?php $do_render_html = (isset($do_render_html)) ? $do_render_html : true ?>

<div class="content_element" id="content_element_<?php echo $element_data['id'] ?>">

  <?php include_partial('ullTableTool/ullContentElementHtmlAndControls', array(
    'element_data'    => $element_data,
    'element_types'   => $element_types,
    'field_id'        => $field_id,
    'do_render_html'  => $do_render_html 
  )) ?>
  
  <?php include_component('ullTableTool', 'ullContentElementForm', array(
    'element_data'    => $element_data,
    'element_types'   => $element_types,
    'field_id'        => $field_id,
  )) ?>      

</div>
