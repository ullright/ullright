<?php
?>

<?php echo $sf_data->getRaw('breadcrumbTree')->getHtml() ?>

<?php echo form_tag('ullTableTool/update', 'id="ull_tabletool_form"'); ?>


<?php if ($sf_request->hasErrors()): ?>
  <div class='form_error'>
  <?php echo __('Please correct the following errors', null, 'common') ?>:
  </div>  
  <br /><br />
<?php endif; ?>
    
  
<table>
<tbody>


<?php foreach ($sf_data->getRaw('ull_form')->getFieldsInfo() as $field_name => $field_info): ?>
  <?php if (@$field_info['enabled']): ?>
    <tr>
      <td>
      <?php //weflowTools::printR($col); ?>
        <?php echo $field_info['name_humanized'] . ':'; ?>
      </td>
      <td>  
        <?php 
          $fields_data  = $sf_data->getRaw('ull_form')->getFieldsDataOne();
          $field_data   = $fields_data[$field_name]; 

//          ullCoreTools::printR($field_data);

          if ($value = @$field_data['value']) {
            echo $value; 

          } elseif (@$field_data['function']) {
            echo call_user_func_array($field_data['function'], $field_data['parameters']);

          }

          if (@$field_info['primary_key']) {
            echo input_hidden_tag($field_name, $id);
          }

          echo form_error($field_name);
          
//          echo 'req:' . $sf_params->get($field_name);
          ?>
  
      </td>
    </tr>
  <?php endif; // end of enabled ?>
<?php endforeach; ?>  

</tbody>
</table>


<br />


<div class='action_buttons_edit'>
<fieldset>
  <legend><?php echo __('Actions', null, 'common') ?></legend>
  
  <div class='action_buttons_edit_left'>

    <ul>

      <li>
        <?php echo submit_tag(__('Save', null, 'common')) ?>
      </li>

    </ul>
    
  </div>

  <div class='action_buttons_edit_right'>

    <ul>

      <li>
<?php
      echo ull_link_to(
        __('Cancel', null, 'common') 
        , url_for('ullTableTool/list?table=' . $table_name)
        , 'ull_js_observer_confirm=true'
      );
?>
      </li>
      <li>
		    <?php if ($id): ?>    
		      <?php 
		        echo link_to(
		          __('Delete', null, 'common')
		          , 'ullTableTool/delete?table=' . $table_name . '&id=' . $id
		          , 'confirm='.__('Are you sure?', null, 'common')
		          ); 
		      ?> &nbsp; 
		    <?php endif; ?>
      </li>
      
    </ul>
    
  </div>

  <div class="clear"></div>  
  
</fieldset>

</div>


<?php echo input_hidden_tag('table', $table_name) ?>
</form>   

<?php
  echo ull_js_observer("ull_tabletool_form");
//  ullCoreTools::printR($ull_form);
?>  
