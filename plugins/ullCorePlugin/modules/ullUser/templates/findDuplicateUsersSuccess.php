<h1>
  <?php echo __('List of duplicate users', null, 'ullCoreMessages') ?>
</h1>

<p>
  <?php echo __('Found %num% users with duplicate names', array('%num%' => count($users)),
      'ullCoreMessages') ?>
</p>

<?php foreach ($users as $user): ?>

  <?php if (!$user['display_name']): ?>
    <?php continue ?>
  <?php endif ?>

  <h3>
    <?php echo ull_link_to(
      $user['display_name'] . '(' . $user['num'] . ')' , 
      'ullUser/list?filter[search]=' . $user['display_name'],
      array('link_new_window' => true)
      
    ) ?>
  </h3>

<?php endforeach ?>

