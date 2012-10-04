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

<?php if (count($warnings)): ?>
  <div id="csv_warnings">
    
    <h2><?php  echo __('Warnings', null, 'common') ?></h2>
  
    <div id="csv_mapping_errors">
      <ul id="csv_mapping_error_list">
        <?php foreach ($warnings as $warning): ?>
          <li>
            <?php echo $warning ?>
          </li>
        <?php endforeach ?>  
      </ul>
    </div>
  
  </div>
<?php endif ?>  
  

<?php if ($form->hasErrors() || count($errors)): ?>
  <div id="csv_errors">
  
    <h2><?php  echo __('Errors', null, 'common') ?></h2>
    
    <div id="csv_global_errors">
      <?php include_partial('ullTableTool/globalError', array('form' => $form)) ?>
    </div>
  
    <div id="csv_row_errors">
      <?php if (count($errors)): ?>
        <p>
          <?php echo __('The following %number% rows could not be imported',
            array('%number%' => count($errors)), 'ullCoreMessages') ?>:
        </p>
      <?php endif ?>
      
      <?php echo ull_form_tag(array(
        'action' => 'csvExport',
        'filename' => $sf_params->get('module') . '_import_errors'
      ), array('target' => '_blank')) ?>
      
        <input type="hidden" name="data" 
          value="<?php echo htmlentities(json_encode($error_data)) ?>"
        />
        <input type="submit" 
          value="<?php echo __('Download errors as csv file', null, 'ullCoreMessages') ?>"
        />      
      </form>
      
      
      <?php foreach ($errors as $error): ?>
        <div id="csv_row_error">
          <h3>
            <?php echo __('Line %number%', array('%number%' => $error['row_number']), 'ullCoreMessages') ?> 
            
              "<?php echo ullCoreTools::print_r_ordinary($error['row_data']) ?>"
            :
          </h3> 
          
          <ul id="csv_row_error_list">
            <?php if (array_key_exists('global_errors', $error)): ?>
              <li><?php echo $error['global_errors'] ?></li>
            <?php endif ?>
          
            <li>
              <b><?php echo $error['field_error']['label'] ?>:</b>
              <span class="form_error"><?php echo $error['field_error']['error'] ?></span>
              "<?php echo $error['field_error']['value'] ?>"
            </li>                      
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
            &nbsp; &nbsp; <?php echo __('Please be patient, this can take some time', 
              null, 'ullCoreMessages') ?>
          </li>
      </ul>
  </div>

  <div class="clear"></div>  
  
</div> <!-- end of edit action buttons -->


</div>
</form>   

