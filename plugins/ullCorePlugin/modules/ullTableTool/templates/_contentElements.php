<?php $elements_markup = $sf_data->getRaw('elements_markup') ?>
<?php $value = $sf_data->getRaw('value') ?>
<?php $field = $sf_data->getRaw('field') ?>

<div id="content_elements_<?php echo $field_id ?>">

  <?php foreach ($elements_markup as $element_id => $element_markup): ?>
    <div class="content_element_markup" id="content_element_markup_<?php echo $element_id ?>">
      <?php echo $element_markup ?>
    </div>
  <?php endforeach ?>

  <?php 
    // This is a proxy field to build / modify the elements actual form field
    // since we cannot perform this in the form field directly
  ?>
  <div id="<?php echo $field_id ?>_proxy" <?php // style="display:none;"?> >
    <?php echo $value ?>
  </div> 
    
  <?php // Render the the actual form field ?>
  <?php echo $field ?>  

</div>
    
