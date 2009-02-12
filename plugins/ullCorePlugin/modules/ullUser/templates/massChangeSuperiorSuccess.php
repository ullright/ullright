<?php //echo $sf_data->getRaw('breadcrumb_tree')->getHtml() ?>
<br />
<h3><?php echo __('Superior mass change') ?></h3>
<br />
<form action="<?php echo url_for('ullUser/massChangeSuperior') ?>" method="post">
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
        	<li><?php echo submit_tag(__('Save superior change')) ?></li>
        </ul>
      </div>
      
      <div class='edit_action_buttons_right'>
        <ul>
        	<li>
        	<?php echo ull_link_to(__('Cancel', null, 'common'), 'ullAdmin/index') ?>
        	</li>
        </ul>
      </div>
      
      <div class="clear"></div>
    </div>
  </div>
</form>

