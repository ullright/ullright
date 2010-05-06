<?php if (sfConfig::get('app_ull_user_enable_registration', false)): ?>
  <li>
    <?php if ($username): // check if logged in ?>
      <?php echo link_to(__('My account', null, 'ullCoreMessages'), 
        'ullUser/editAccount?username=' . $username) ?>
    <?php else: ?>
      <?php echo link_to(__('Register', null, 'ullCoreMessages'), 
        'ullUser/register')?>  
    <?php endif ?>
  </li>
<?php endif ?>