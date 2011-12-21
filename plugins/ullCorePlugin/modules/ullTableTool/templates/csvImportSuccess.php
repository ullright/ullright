<?php if (isset($breadcrumb_tree)): ?>
  <?php echo $breadcrumb_tree ?>
<?php endif ?>

<h1>
  <?php echo __('CSV Import', null, 'ullCoreMessages') ?>
</h1>

<?php if ($number_rows_imported): ?>
  <div id="csv_success">
    <p>
      <?php echo __('%number% rows sucessfully imported', array('%number%' => $number_rows_imported), 'ullCoreMessages') ?>.
    </p>
  </div>
<?php endif ?>

<?php if (count($mapping_errors)): ?>
  <div id="csv_warnings">
    
    <h2><?php  echo __('Warnings', null, 'common') ?></h2>
  
    <div id="csv_mapping_errors">
      <ul id="csv_mapping_error_list">
        <?php foreach ($mapping_errors as $error): ?>
          <li>
            <?php echo $error ?>
          </li>
        <?php endforeach ?>  
      </ul>
    </div>
  
  </div>
<?php endif ?>  
  

<?php if ($form->hasErrors() || count($generator_errors)): ?>
  <div id="csv_errors">
  
    <h2><?php  echo __('Errors', null, 'common') ?></h2>
  
    <div id="csv_global_errors">
      <?php include_partial('ullTableTool/globalError', array('form' => $form)) ?>
    </div>
  
    <div id="csv_row_errors">
      <?php if (count($generator_errors)): ?>
        <p><?php echo __('The following rows could not be imported', null, 'ullCoreMessages') ?>:</p>
      <?php endif ?>
      
      <?php foreach ($generator_errors as $row_number => $generator_error): ?>
        <div id="csv_row_error">
          <h3>
            <?php echo __('Line %number%', array('%number%' => $row_number), 'ullCoreMessages') ?> 
            
              "<?php echo ullCoreTools::print_r_ordinary($generator_error->getForm()->getTaintedValues()) ?>"
            :
          </h3> 
          
          <ul id="csv_row_error_list">
            <?php foreach ($generator_error->getForm()->getErrorSchema()->getErrors() as $fieldName => $error): ?>
              <li>
                <?php echo str_replace(' *', '', $generator_error->getForm()->offsetGet($fieldName)->renderLabel()) ?>:  
                <?php echo $generator_error->getForm()->offsetGet($fieldName)->renderError() ?>
                <?php if ($value = $error->getValue()): ?>
                  "<?php echo ullCoreTools::print_r_ordinary($value) ?>"
                <?php endif ?>
              </li>
            <?php endforeach // error per row ?>  
          </ul>
          
        </div>
      <?php endforeach //row ?>
  </div>
  
  <!-- end of csv_errors -->
  </div>
<?php endif ?>

<?php echo form_tag(
  $sf_context->getModuleName() . '/' . $sf_context->getActionName(), 
  array('multipart' => 'true')
) ?>

<div class="edit_container">

<?php if (isset($custom_message)): ?>
  <p id="csv_custom_message">
    <?php echo $custom_message ?>
  </p>
<?php endif?>


<?php include_partial('ullTableTool/flash', array('name' => 'message')) ?>

<table class="edit_table">
<tbody>

<?php echo $form ?>

</tbody>
</table>



<div class='edit_action_buttons color_light_bg'>
  <h3><?php echo __('Actions', null, 'common')?></h3>
  
  <div class='edit_action_buttons_left'>
      <ul>
          <li>
            <?php             
              echo ull_submit_tag(__('Upload', null, 'common'))  
            ?>
          </li>
      </ul>
  </div>

  <div class="clear"></div>  
  
</div> <!-- end of edit action buttons -->


</div>
</form>   

