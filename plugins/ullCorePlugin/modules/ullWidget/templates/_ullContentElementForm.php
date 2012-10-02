<?php /* Note: this file is used both as component and partial */ ?>

<?php $generator = $sf_data->getRaw('generator') ?>
<?php $element_types = $sf_data->getRaw('element_types') ?>
<?php $element_data = $sf_data->getRaw('element_data') ?>
<?php $element_id = $element_data['id'] ?>
<?php $element_type = $element_data['type'] ?>

<div class="content_element_form content_element_form_<?php echo $element_type ?>"
  id="content_element_form_<?php echo $element_id ?>">
  
  <?php // Note that we have no form tag, as nesting is not allowed ?>
  
  <?php include_partial('ullTableTool/globalError', array(
    'form' => $generator->getForm()
  )) ?>
  
  <?php include_partial('ullTableTool/editTable', array(
    'generator' => $generator
  )) ?>
  
  <div class='edit_action_buttons'>
  
    <?php $url = url_for('ullWidget/contentElement?' .
        'element_types=' . urlencode(json_encode($element_types)) . '&' .
        'element_type=' . $element_type . '&' .
        'element_id=' . $element_id . '&' .
        'field_id=' . $field_id
    ) ?>
  
    <?php echo button_to_function(
      __('Save', null, 'common'),
      'contentElementSubmit(' .
        '\'' . $element_id . '\', ' .
        '\'' . $url . '\',' .
        '\'' . $field_id . '\'' .
      ')'
    ) ?>
    
    <?php echo ull_image_tag_indicator(array(
      'style' => 'display: none;',
      'id'    => 'content_element_indicator_' . $element_id,
    )) ?>
    
    <?php echo link_to_function(
      __('Cancel', null, 'common'),
      'contentElementCancel(' .
        '\'' . $element_id . '\' ' .
      ')'
    ) ?> 
    
    <?php /*
    // all jQuery events are executed within the document ready function
$(document).ready(function() {

   $("input").bind("keydown", function(event) {
      // track enter key
      var keycode = (event.keyCode ? event.keyCode : (event.which ? event.which : event.charCode));
      if (keycode == 13) { // keycode for enter key
         // force the 'Enter Key' to implicitly click the Update button
         document.getElementById('defaultActionButton').click();
         return false;
      } else  {
         return true;
      }
   }); // end of function

}); // end of document ready
*/ ?>
    
  </div>
      
</div>