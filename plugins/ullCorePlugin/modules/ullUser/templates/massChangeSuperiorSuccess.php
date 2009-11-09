<?php echo $breadcrumb_tree ?>

<h3><?php echo __('Superior mass change') ?></h3>
<br/>

<?php include_partial('ullTableTool/flash', array('name' => 'message')) ?>

<?php include_partial('ullTableTool/global_error', array('form' => $form)) ?>

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
        	<li><?php echo submit_tag(__('Save', null, 'common')) ?></li>
        </ul>
      </div>
      
      <div class='edit_action_buttons_right'>
      </div>
      
      <div class="clear"></div>
    </div>
  </div>
</form>

