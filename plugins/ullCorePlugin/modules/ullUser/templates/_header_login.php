            <?php if ($logged_in_user_id = $sf_user->getAttribute('user_id')): ?>
              <?php echo __('Logged in as', null, 'common').' '
                .UllUserPeer::retrieveByPK($logged_in_user_id)->getUsername().
                ' - '; ?>
              <?php echo link_to(__('Log out', null, 'common'), 'ullUser/logout') ?>              
            <?php else: ?>
              <?php echo link_to(__('Log in', null, 'common'), 'ullUser/login') ?>
            <?php endif ?>