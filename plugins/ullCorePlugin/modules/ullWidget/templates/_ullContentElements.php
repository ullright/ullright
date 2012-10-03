<?php $element_types = $sf_data->getRaw('element_types') ?>
<?php $elements_data = $sf_data->getRaw('elements_data') ?>
<?php $field = $sf_data->getRaw('field') ?>

<div id="content_elements_<?php echo $field_id ?>" class="content_elements">

  <?php // Render the the actual form field ?>
  <?php echo $field ?>  

  <?php include_partial('ullWidget/ullContentElement', array(
    'element_data'    => array(
      'id' => 'dummy_first_' . $field_id, 
      'type' => key($element_types), // take first type as default
      'values' => array(),
    ), 
    'element_types'   => $element_types, 
    'field_id'        => $field_id,
    'do_render_html'  => false,
    'do_render_form'  => false,
    'css_class'       => $css_class,
  )) ?>
  
  <?php foreach ($elements_data as $element_data): ?>
  
      <?php include_partial('ullWidget/ullContentElement', array(
        'element_data'    => $element_data,
        'element_types'   => $element_types,
        'field_id'        => $field_id,
        'css_class'       => $css_class,
      )) ?>
      
  <?php endforeach ?>

  <?php echo javascript_tag('

contentElementInitialize(\'' . $field_id . '\');      
      
') ?>
  
  
  
 

</div>
    
