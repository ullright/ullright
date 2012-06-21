<?php if ($username): ?>
  <li id="logged_in_as">
    <?php echo __('Logged in as', null, 'common') ?>
    <?php echo ull_link_entity_popup($username, $sf_user->getAttribute('user_id')) ?>
    -
    <?php echo ull_link_to(__('Log out', null, 'common'), 'ullUser/logout', 'ull_js_observer_confirm=true') ?>
  </li>
<?php else: ?>
  <li id="log_in">
    <?php echo ull_link_to(__('Log in', null, 'common'), 'ullUser/login', 'ull_js_observer_confirm=true') ?>
  </li>
<?php endif ?>
