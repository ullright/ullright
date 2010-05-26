<h2><?php echo __('Log in', null, 'common') ?></h2>

<?php include_partial('ullTableTool/flash', array('name' => 'message')) ?>
<noscript>
  <div class="flash">
    <?php echo __('JavaScript recommended for improved user experience.', null, 'ullCoreMessages')?>
  </div>
</noscript>

<?php if ($renderForm): ?> 
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
    <div class='edit_action_buttons_right'>
      <ul>
      <?php
        if(sfConfig::get('app_ull_user_enable_sign_up', false))
        {
          echo '<li>' . ull_link_to(
            __('No account yet? Click here to sign up!', null, 'ullCoreMessages'),
             'ullUser/signUp'
          ) . '</li>'; 
        }  
        if(sfConfig::get('app_ull_user_enable_reset_password', false))
        {
          echo '<li>' . ull_link_to(
            __('I forgot my password', null, 'ullCoreMessages'),
             'ullUser/resetPassword'
          ) . '<li>'; 
        }
      ?>
      </ul>
    </div>
  </div>
  
</div>
 
</form>
<?php endif ?>

<?php use_javascripts_for_form($form) ?>
<?php use_stylesheets_for_form($form) ?>