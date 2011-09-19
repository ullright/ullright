<?php /* The first line is the subject */ 
  echo __(
    'Your login details for %site%', 
    array('%site%' => sfConfig::get('app_server_name', 'www.example.com')),
    'ullCoreMessages'
  ) 
?> 
<p>
  <?php echo __(
    'Someone, possibly you, requested the login details for %site%',
    array('%site%' => sfConfig::get('app_server_name', 'www.example.com')),
    'ullCoreMessages'
  ) ?>.
</p>

<p>
  <?php echo __(
    'If you did not request your login details please ignore this email',
    null,
    'ullCoreMessages'
  ) ?>.
</p>

<?php if (count($users) > 1): ?>
  <p>
    <?php echo __(
      'Your email address is used in multiple accounts. Please choose yours:',
      null,
      'ullCoreMessages'
    ) ?>
  </p>
<?php endif ?>

<?php foreach ($users as $user): ?>
  <?php $token = UllUserOneTimeTokenTable::createToken($user->id)?>
  
  <h3>
    <?php echo strtoupper($user['first_name'] . ' ' . $user['last_name']) ?>:
  </h3>
  
  <ul>
    <li> 
      <?php echo __('Username', null, 'common') ?>: <?php echo $user['username']?><br /> &nbsp;
    </li> 
    <li>
      <?php $url = url_for('ullUser/newPassword?s_user_id=' . $user['id'] . '&token=' . $token, true) ?>
      <?php echo __('Enter a new password', null, 'ullCoreMessages') ?>:
      <?php echo link_to($url, $url) ?>
    </li>
  </ul>
<?php endforeach ?>







