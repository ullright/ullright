<?php if ($logged_in_user_id = $sf_user->getAttribute('user_id')): ?>
  <?php echo __('Logged in as', null, 'common').' '
    .UllUserPeer::retrieveByPK($logged_in_user_id)->getUsername().
    ' - '; ?>
  <?php echo ull_link_to(__('Log out', null, 'common'), '/ullUser/logout', 'ull_js_observer_confirm=true') ?>              
<?php else: ?>
  <?php echo ull_link_to(__('Log in', null, 'common'), '/ullUser/login', 'ull_js_observer_confirm=true') ?>
<?php endif ?>