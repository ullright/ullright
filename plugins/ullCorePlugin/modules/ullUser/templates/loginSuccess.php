<?php echo form_tag('ullUser/login') ?>

  <br /><br /><br />
  
  <?php if (@$msg): ?>
    <em><?php echo $msg; ?></em><br /><br />
  <?php endif; ?>

  <table>
    <tr>
      <td><?php echo __('Username').':'; ?></td>
      <td><?php echo input_tag('username', $sf_params->get('username')); ?></td>
      <td><?php echo form_error('username'); ?></td>
    </tr>
    <tr>
      <td><?php echo __('Password').':'; ?></td>
      <td><?php echo input_password_tag('password'); ?></td>
      <td><?php echo form_error('password'); ?></td>
    </tr>
  </table>

  <?php echo input_hidden_tag('js_check', '0'); ?>
  <?php echo javascript_tag('document.getElementById("js_check").value = 1;'); ?>

  <?php echo javascript_tag('document.getElementById("username").focus();'); ?>
  
  <br />
  
  <?php //echo input_hidden_tag('referer', $sf_request->getAttribute('referer')) ?>
  <?php echo submit_tag(__('Log in')); ?>
 
</form>

<?php
// highlight error fields
foreach ($sf_request->getErrors() as $error_field => $error_msg) {
  echo javascript_tag("
    document.getElementById(\"$error_field\").className = \"form_error_background\";
  ");
}
?>