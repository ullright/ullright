<?php

  echo '<h1>' . __('Manage files', null, 'common') . '</h1><br />';

  echo ull_form_tag(array('fields' => ''), 'multipart=true id=ull_flow_upload_form');
  
//  echo input_hidden_tag('external_field', $external_field);
//  echo input_hidden_tag($external_field, $value);
//  echo input_hidden_tag('app', $app);
//  echo input_hidden_tag('doc', $doc);
//  echo input_hidden_tag('ull_flow_action', $ull_flow_action);

  if ($value = $form->getDefault('value')) 
  {
    echo ullWidgetUpload::renderUploadList($value);
  } 
?>

<br />
<h3><?php echo __('Upload new file', null, 'common'); ?>:</h3>
  
<ul>
  <li>
    <?php
      echo __('Step 1: Select file', null, 'common') . ': ';
      echo $form['file']->render();
    ?>
  </li> 
  
  <li>
    <?php 
      echo __('Step 2', null, 'common') . ': ' . submit_tag(__('Upload file', null, 'common'));
    ?>
  </li>
</ul>

  
<?php echo $form['value']->render() // TODO: replace by sf1.2 sfForm::renderHiddenFields() ?>
  
</form>
  
  <br /><br />
  
  <div class='action_buttons'>
    
    <div class='action_buttons_left'>
      <?php echo ull_button_to(__('Save and close'), 'ullFlow/edit?doc=' . $doc->id) ?>
      <?php //echo form_tag('ullFlow/edit?doc=' . $doc->id) ?>
      <?php //echo input_hidden_tag('fields[' . $column . ']', $value) ?>
      <?php //echo submit_tag(__('Save and close', null, 'common')); ?>
      </form>
    </div>
    
    <div class="clear"></div>
  </div>
  
  
  