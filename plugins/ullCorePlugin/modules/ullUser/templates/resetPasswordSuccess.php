<h2><?php echo __('Please enter your username', null, 'ullCoreMessages') ?></h2>

<?php include_partial('ullTableTool/flash', array('name' => 'message')) ?>

<?php echo form_tag('ullUser/resetPassword') ?>
  
<div class="edit_container">
  <table class="edit_table">
    <tbody>
      <?php echo $form ?>
    </tbody>
  </table>

<?php echo javascript_tag('document.getElementById("resetPassword_username").focus();') ?>
  
  <div class='edit_action_buttons color_light_bg'>
    <div class='edit_action_buttons_left'>
      <?php echo submit_tag(__('Send me a new password', null, 'ullCoreMessages')); ?>
    </div>
  </div>
  
</div>
 
</form>

<?php use_javascripts_for_form($form) ?>
<?php use_stylesheets_for_form($form) ?>