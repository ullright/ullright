<h2><?php echo __('Log in', null, 'common') ?></h2>

<?php include_partial('ullTableTool/flash', array('name' => 'message')) ?>

<?php echo form_tag('ullUser/login') ?>
  
<div class="edit_container">
  <table class="edit_table">
    <tbody>
      <?php echo $form ?>
    </tbody>
  </table>

<?php echo javascript_tag('document.getElementById("login_js_check").value = 1;') ?>
<?php echo javascript_tag('document.getElementById("login_username").focus();') ?>
  
  <div class='edit_action_buttons color_light_bg'>
    <div class='edit_action_buttons_left'>
      <?php echo submit_tag(__('Log in')); ?>
    </div>
  </div>
  
</div>
 
</form>
