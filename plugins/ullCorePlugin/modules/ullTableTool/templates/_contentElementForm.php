<div class="content_element_form content_element_form_<?php echo $element ?>"
  id="content_element_form_<?php echo $id ?>">
  
  <?php include_partial('ullTableTool/globalError', array(
    'form' => $generator->getForm()
  )) ?>
  
  <?php include_partial('ullTableTool/editTable', array(
    'generator' => $generator
  )) ?>
  
  <div class='edit_action_buttons'>
  
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
    <?php // Note: that the "table" param above is not actually used ?>
  </div>
      
</div>