<?php include_partial('ullTableTool/jQueryRequirements')?>

<?php echo $sf_data->getRaw('breadcrumb_tree')->getHtml() ?>

<?php $generator = $sf_data->getRaw('generator') ?>

<?php if ($generator->getForm()->getErrorSchema()->getErrors()): ?>
  <div class='form_error'>
  <?php echo __('Please correct the following errors', null, 'common') ?>:
  </div>  
  <br /><br />
<?php endif; ?>

<?php echo form_tag($sf_context->getModuleName() . '/edit?table=' . $table_name . '&' . $generator->getIdentifierUrlParams(0), 
    array('id' => 'ull_tabletool_form')) ?>

<div class="edit_container">
<table class="edit_table">
<tbody>

<?php echo $generator->getForm() ?>

</tbody>
</table>


<br />

<div class='edit_action_buttons color_light_bg'>
  <h3><?php echo __('Actions', null, 'common')?></h3>
  
  <div class='edit_action_buttons_left'>
    <ul>
      <li>
        <?php echo submit_tag(__('Save', null, 'common')) ?>
      </li>
    </ul>
  </div>

  <div class='edit_action_buttons_right'>
    <ul>
      <li>
      <?php
      echo ull_link_to(
        __('Cancel', null, 'common') 
        , $refererHandler->getReferer('edit')
        , 'ull_js_observer_confirm=true'
      );
      ?>
      </li>
      <li>
		    <?php if ($generator->getRow()->exists()): ?>    
		      <?php 
		        echo link_to(
		          __('Delete', null, 'common')
		          , 'ullTableTool/delete?table=' . $table_name . '&' . $generator->getIdentifierUrlParams(0)
		          , 'confirm='.__('Are you sure?', null, 'common')
		          ); 
		      ?> &nbsp; 
		    <?php endif; ?>
      </li>
    </ul>
  </div>

  <div class="clear"></div>  
  
</div>
</div>
</form>   

<?php 
  echo ull_js_observer("ull_tabletool_form");
?>  


