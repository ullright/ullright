<br />

<?php echo ull_form_tag(array('fields' => ''), 'multipart=true id=ull_flow_upload_form'); ?>

<?php if ($form->hasErrors()): ?>
  <div class='form_error'>
  <?php echo __('Please correct the following errors', null, 'common') ?>:
  <?php echo $form->renderGlobalErrors() ?>
  </div>  
  <br /><br />
<?php endif; ?>

<div class="ull_upload">
  <?php
    echo '<h3>' . __('Manage files', null, 'common') . '</h3><br />';
  
    if ($value = $form->getDefault('value')) 
    {
      echo ullWidgetUpload::renderUploadList($value);
    } 
  ?>
</div>

<br />

<div class="ull_upload color_light_bg">

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

  <?php echo $form->renderHiddenFields()?>
  <br /><br />


  <div class='action_buttons_left'>
      <?php echo ull_button_to(__('Save and close', null, 'common'), 'ullFlow/edit?doc=' . $doc->id) ?>
      <div class="clear"></div>
  </div>

</div>

</form>

<br />


  
  
  
  
  