<?php $element_data = $sf_data->getRaw('element_data') ?>
<?php $element_id = $element_data['id'] ?>
<?php $element_types = $sf_data->getRaw('element_types') ?>
<?php $css_class = (isset($css_class)) ? $css_class : null ?>


<div class="content_element_controls_add" 
  id="content_element_controls_add_<?php echo $element_id ?>" >

  <div class="content_element_controls_add_button" 
    onclick="contentElementAddList('<?php echo $element_id ?>'); return false;"
    style="cursor: pointer;">
    +
  </div>  
  
  <div class="content_element_controls_add_list">
  <h2><?php echo __('Add an element', null, 'ullCoreMessages') ?>:</h2>
  
  <ul>
    <?php $element_types_json = urlencode(json_encode($element_types)) ?>
    <?php foreach($element_types as $element_type => $name): ?>
    
      <?php $url = url_for('ullWidget/contentElementAdd?' .
          'field_id=' . $field_id. '&' .
          'element_type=' . $element_type . '&' .
          'element_types=' . $element_types_json . '&' . 
          'css_class=' . $css_class          
      ) ?>      
      
      <li onclick="contentElementAdd(
        '<?php echo $element_type ?>',
        '<?php echo $element_id ?>',
        '<?php echo $url ?>',
        '<?php echo $field_id ?>'
      ); return false;" style="cursor: pointer;">
        <?php echo $name ?>
      </li>
    <?php endforeach ?>
  </ul>
  </div>
  
</div>


