<?php $element_types = $sf_data->getRaw('element_types') ?>
<?php $elements_data = $sf_data->getRaw('elements_data') ?>
<?php $field = $sf_data->getRaw('field') ?>

<div id="content_elements_<?php echo $field_id ?>" class="content_elements">

  <?php foreach ($elements_data as $element_data): ?>
  
      <?php include_partial('ullWidget/ullContentElement', array(
        'element_data'    => $element_data,
        'element_types'   => $element_types,
        'field_id'        => $field_id,
      )) ?>
      
  <?php endforeach ?>

  <?php 
    // This is a proxy field to build / modify the elements actual form field
    // since we cannot perform this in the form field directly
  ?>
  <!-- 
  <div id="<?php echo $field_id ?>_proxy" style="display:none;" >
    <?php //echo $value ?>
  </div>
   -->
    
  <?php // Render the the actual form field ?>
  <?php echo $field ?>  

</div>
    
