<?php $element_data = $sf_data->getRaw('element_data') ?>
<?php $element_id = $element_data['id'] ?>
<?php $element_types = $sf_data->getRaw('element_types') ?>


<div class="content_element_controls_add" 
  id="content_element_controls_add_<?php echo $element_id ?>" >

  <div class="content_element_controls_add_button" onclick="enlargeClickArea(this)">
    <?php echo link_to_function(
      '+',
      'contentElementAddList(' .
        '"' . $element_id . '" ' .
      ')'
    ) ?>
  </div>  
  
  <div class="content_element_controls_add_list">
  <h2><?php echo __('Add an element', null, 'ullCoreMessages') ?>:</h2>
  
  <ul>
    <?php $element_types_json = urlencode(json_encode($element_types)) ?>
    <?php foreach($element_types as $element_type => $name): ?>
    
      <?php $url = url_for('ullWidget/contentElementAdd?' .
          'field_id=' . $field_id. '&' .
          'element_type=' . $element_type . '&' .
          'element_types=' . $element_types_json 
      ) ?>      
      
      <li onclick="enlargeClickArea(this)">
        <?php echo link_to_function($name,
          'contentElementAdd(' .
            '"' . $element_type . '", ' .
            '"' . $element_id . '", ' .
            '"' . $url . '", ' .
            '"' . $field_id . '" ' .
          ')'   
        ) ?>
      </li>
    <?php endforeach ?>
  </ul>
  </div>
  
</div>


