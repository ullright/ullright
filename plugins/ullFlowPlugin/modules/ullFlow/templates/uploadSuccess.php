<?php

  echo '<h1>' . __('Manage files', null, 'common') . '</h1><br />';

  echo ull_form_tag(array(), 'multipart=true id=ull_flow_upload_form');
  
//  echo input_hidden_tag('external_field', $external_field);
//  echo input_hidden_tag($external_field, $value);
//  echo input_hidden_tag('app', $app);
//  echo input_hidden_tag('doc', $doc);
//  echo input_hidden_tag('ull_flow_action', $ull_flow_action);

  if ($value) 
  {
    echo ullWidgetUploadRead::renderUploadList($value);
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
  
  <br /><br />
  
  <div class='action_buttons'>
    
    <div class='action_buttons_left'>
      <?php //echo button_to_function(__('Save and close', null, 'common'), 'return_to()'); ?>
      <?php echo submit_tag(__('Save and close', null, 'common')); ?>
    </div>
    
    <div class="clear"></div>
  </div>
  
  </form>
  
<?php
  
  echo javascript_tag('
    function return_to() {
      document.getElementById("ull_flow_upload_form").action = "' . url_for('ullFlow/update') . '"
      document.getElementById("external_field").value = "";      
      document.getElementById("ull_flow_upload_form").submit();
    }
  ');
      
?>