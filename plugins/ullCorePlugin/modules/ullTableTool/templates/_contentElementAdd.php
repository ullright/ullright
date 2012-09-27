<?php $elements = $sf_data->getRaw('elements') ?>

<div class="content_element_add" 
  id="content_element_add_<?php echo $element_id ?>" >

  <div class="content_element_add_button">
    <?php echo link_to_function(
      '+',
      'contentElementAddList(' .
        '"' . $element_id . '", ' .
        '"' . $field_id . '" ' .
      ')'
    ) ?>
  </div>  
  
  <div class="content_element_add_list">
  <h2><?php echo __('Add an element', null, 'ullCoreMessages') ?></h2>
  
  <ul>
    <?php $elements_json = urlencode(json_encode($elements)) ?>
    <?php foreach($elements as $element => $name): ?>
    
      <?php $url = url_for('ullTableTool/contentElementAdd?' .
          'table=UllContentElement&' .
          'element=' . $element . '&' .
          'field_id=' . $field_id. '&' .
          'elements=' . $elements_json 
      ) ?>      
      
      <li>
        <?php echo link_to_function($name,
          'contentElementAdd(' .
            '"' . $element . '", ' .
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


