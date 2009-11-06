<br />
<h2><?php echo __('Log in', null, 'common'); ?></h2>
<br />
<?php echo form_tag('ullUser/login') ?>
  
  <?php if (isset($msg)): ?>
    <em><?php echo $msg; ?></em><br /><br />
  <?php endif; ?>

  <div class="edit_container">
    <table class="edit_table">
      <tbody>
    <?php echo $form ?>
    </tbody>
  </table>

<?php echo javascript_tag('document.getElementById("login_js_check").value = 1;'); ?>
<?php echo javascript_tag('document.getElementById("login_username").focus();'); ?>
  
<br />
    <div class='edit_action_buttons color_light_bg'>
      <div class='edit_action_buttons_left'>
        <?php echo submit_tag(__('Log in')); ?>
      </div>
      
      <div class='edit_action_buttons_right'>
        <ul>
          <li>
          <?php //echo ull_link_to(__('Cancel', null, 'common'), '@homepage') ?>
          </li>
        </ul>
      </div>
      <div class="clear"></div>
    </div>
  </div>
  
  <br />
 
</form>

<?php
// highlight error fields
/*@var $form LoginForm*/
foreach ($form->getErrorSchema()->getErrors() as $error_field => $error) 
{
  echo javascript_tag("
    document.getElementById(\"login_$error_field\").className = \"form_error_background\";
  ");
}
?>