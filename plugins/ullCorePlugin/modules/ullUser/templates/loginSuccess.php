<?php echo form_tag('ullUser/login') ?>

  <br /><br /><br />
  
  <?php if (@$msg): ?>
    <em><?php echo $msg; ?></em><br /><br />
  <?php endif; ?>

  <table>
    <?php echo $form ?>
  </table>

  <?php echo javascript_tag('document.getElementById("login_js_check").value = 1;'); ?>
  <?php echo javascript_tag('document.getElementById("login_username").focus();'); ?>
  
  <br />
  <?php echo submit_tag(__('Log in')); ?>
 
</form>

<?php
// highlight error fields
/*@var $form LoginForm*/
foreach ($form->getErrorSchema()->getErrors() as $error_field => $error) {
  echo javascript_tag("
    document.getElementById(\"login_$error_field\").className = \"form_error_background\";
  ");
}
?>