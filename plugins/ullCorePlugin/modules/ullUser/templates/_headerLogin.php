<li>
  <?php if ($username): ?>
    <?php echo __('Logged in as', null, 'common') ?>
    <?php echo ull_link_entity_popup($username, $sf_user->getAttribute('user_id')) ?>
    -
    <?php echo ull_link_to(__('Log out', null, 'common'), 'ullUser/logout', 'ull_js_observer_confirm=true') ?>
<?php else: ?>
    <?php echo ull_link_to(__('Log in', null, 'common'), 'ullUser/login', 'ull_js_observer_confirm=true') ?>
<?php endif ?>
  </li>