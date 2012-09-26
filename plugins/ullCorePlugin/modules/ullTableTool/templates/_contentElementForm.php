<div class="content_element_form content_element_form_<?php echo $element ?>"
  id="content_element_form_<?php echo $id ?>">
  
  <?php // Note that we have no form tag, as nesting is not allowed ?>
  
  <?php include_partial('ullTableTool/globalError', array(
    'form' => $generator->getForm()
  )) ?>
  
  <?php include_partial('ullTableTool/editTable', array(
    'generator' => $generator
  )) ?>
  
  <div class='edit_action_buttons'>
  
    <?php // Note that the "table" param is not actually used ?>
    <?php $url = url_for('ullTableTool/contentElement?' .
        'table=UllContentElement&' .
        'element=' . $element . '&' .
        'id=' . $id
    ) ?>
  
    <?php echo button_to_function(
      __('Apply', null, 'ullCoreMessages'),
      'contentElementSubmit(' .
        '"' . $id . '", ' .
        '"' . $url . '"' .
      ')'
    ) ?>
    
    <?php echo ull_image_tag_indicator(array(
      'style' => 'display: none;',
      'id'    => 'content_element_indicator_' . $id,
    )) ?>
    
  </div>
      
</div>