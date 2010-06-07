<?php // checking for $username means check if logged in ?>

<?php if (sfConfig::get('app_ull_user_enable_sign_up', false) && !$username): ?>
  <li>
    <?php echo link_to(__('Sign up', null, 'ullCoreMessages'), 
      'ullUser/signUp')?>  
  </li>
<?php endif ?>

<?php if (sfConfig::get('app_ull_user_enable_my_account', false) && $username): ?>
  <li>
    <?php echo link_to(__('My account', null, 'ullCoreMessages'), 
      'ullUser/editAccount') ?>
  </li>
<?php endif ?>
