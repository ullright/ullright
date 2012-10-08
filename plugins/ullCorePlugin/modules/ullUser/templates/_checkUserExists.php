<div class="form_error">
  <?php echo __('Attention: found one or more users with the same name',
    null, 'ullCoreMessages') ?>:
</div>

<?php foreach ($results as $result): ?>
  <div>
    <?php echo ull_link_to(
      __('View user data of %name%', 
        array('%name%' => $first_name . ' ' . $last_name), 'ullCoreMessages'),
      'ullUser/edit?id=' . $result['id'],
      array('link_new_window' => true)
    ) ?>
  </div>
<?php endforeach ?>
