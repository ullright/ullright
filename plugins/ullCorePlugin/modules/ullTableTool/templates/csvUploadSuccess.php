<h1>
  <?php echo __('CSV Import', null, 'ullCoreMessages') ?>
</h1>

<?php if (isset($customMessage)): ?>
  <div id="csv_custom_message">
    <?php echo $customMessage ?>
  </div>
<?php endif?>

<?php if ($numberRowsImported): ?>
  <div id="csv_success">
    <p>
      <?php echo __('%number% rows sucessfully imported', array('%number%' => $numberRowsImported), 'ullCoreMessages') ?>.
    </p>
  </div>
<?php endif ?>

<?php if (count($mappingErrors)): ?>
  <div id="csv_warnings">
    
    <h2><?php  echo __('Warnings', null, 'common') ?></h2>
  
    <div id="csv_mapping_errors">
      <ul id="csv_mapping_error_list">
        <?php foreach ($mappingErrors as $error): ?>
          <li>
            <?php echo $error ?>
          </li>
        <?php endforeach ?>  
      </ul>
    </div>
  
  </div>
<?php endif ?>  
  

<div id="csv_errors">

  <h2><?php  echo __('Errors', null, 'common') ?></h2>

  <div id="csv_global_errors">
    <?php include_partial('ullTableTool/globalError', array('form' => $form)) ?>
  </div>

  <div id="csv_row_errors">
    <?php if (count($generatorErrors)): ?>
      <p><?php echo __('The following rows could not be imported', null, 'ullCoreMessages') ?>:</p>
    <?php endif ?>
    
    <?php foreach ($generatorErrors as $rowNumber => $generatorError): ?>
      <div id="csv_row_error">
        <h3>
          <?php echo __('Line %number%', array('%number%' => $rowNumber), 'ullCoreMessages') ?> 
          <?php if ($toString = (string) $generatorError->getForm()->getObject()): ?>
            "<?php echo $toString ?>"
          <?php endif ?>
          :
        </h3> 
        
        <ul id="csv_row_error_list">
          <?php foreach ($generatorError->getForm()->getErrorSchema()->getErrors() as $fieldName => $error): ?>
            <li>
              <?php echo str_replace(' *', '', $generatorError->getForm()->offsetGet($fieldName)->renderLabel()) ?>:  
              <?php echo $generatorError->getForm()->offsetGet($fieldName)->renderError() ?>
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


<?php echo form_tag('myModule/csvUploadTest', array('multipart' => 'true')) ?>

<div class="edit_container">

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

