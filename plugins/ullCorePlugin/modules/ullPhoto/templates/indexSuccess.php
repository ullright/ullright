<?php echo $breadcrumb_tree ?>

<div class="edit_container">

<h3>
  <?php if ($user->exists()): ?>
    <?php echo __('Upload a new photo for %user%', array('%user%' => $user->display_name), 'ullCoreMessages') ?>
  <?php else: ?>
    <?php echo __('Upload new photos', null, 'ullCoreMessages') ?>
  <?php endif ?>
</h3>

<?php include_partial('ullTableTool/flash', array('name' => 'cleared')) ?>
<?php include_partial('ullTableTool/flash', array('name' => 'no_more_photos')) ?>

<?php echo ull_form_tag(array(), 'multipart=true id=edit_form'); ?>  

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
        <?php echo submit_tag(__('Upload', null, 'common')) ?>
      </li>
    </ul>
  </div>

  <div class='edit_action_buttons_right'>
  </div>

  <div class="clear"></div>  
  
</div>


</form>

</div>

