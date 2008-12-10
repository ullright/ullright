<?php echo $sf_data->getRaw('breadcrumb_tree')->getHtml() ?>

<?php if ($generator->getForm()->getErrorSchema()->getErrors()): ?>
  <div class='form_error'>
  <?php echo __('Please correct the following errors', null, 'common') ?>:
  </div>  
  <br /><br />
<?php endif; ?>

<?php echo form_tag('ullTableTool/edit?table=' . $table_name . '&id=' . $id, 
    array('id' => 'ull_tabletool_form')) ?>

<table>
<tbody>

<?php echo $generator->getForm() ?>

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
        __('Cancel', null, 'common'), 
        'ullTableTool/list?table=' . $table_name,
        'ull_js_observer_confirm=true'
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

</form>   

<?php 
  echo ull_js_observer("ull_tabletool_form");
?>  


