<?php echo $sf_data->getRaw('breadcrumbTree')->getHtml() ?>
<h3><?php echo __('Change owner for all items belonging to', null, 'ullVentoryMessages') .
  ': ' . $oldEntityDisplayName ?></h3>
<?php if ($form->hasErrors()): ?>
  <div class='form_error'>
  <?php echo __('Please correct the following errors', null, 'common') ?>:
  <?php echo $form->renderGlobalErrors() ?>
  </div>  
  <br />
<?php endif; ?>
<br />
<form action="<?php echo url_for('ullVentory/massChangeOwner?oldEntityId=' . $oldEntityId) ?>" method="post">
  <div class="edit_container">
    <table class="edit_table">
    	<tbody>
      	<?php echo $form ?>
    	</tbody>
    </table>
  <br />
    <div class='edit_action_buttons color_light_bg'>
      <div class='edit_action_buttons_left'>
        <ul>
        	<li><?php echo submit_tag(__('Change owner', null, 'ullVentoryMessages')) ?></li>
        </ul>
      </div>
      
      <div class='edit_action_buttons_right'>
        <ul>
        	<li>
        	<?php echo ull_link_to(__('Cancel', null, 'common'), 'ullVentory/list') ?>
        	</li>
        </ul>
      </div>
      
      <div class="clear"></div>
    </div>
  </div>
</form>

