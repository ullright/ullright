<?php slot('sidebar') ?>
  <?php echo $sidebar_menu ?>
<?php end_slot() ?>

<h1>
  <?php if ($allow_edit): ?>
    <?php
      echo ull_link_to(ull_image_tag('edit'),
        array('module' => 'ullCms', 'action' => 'edit', 'id' => $doc->id));
    ?>              
  <?php endif ?>
  <?php echo $title ?>
</h1>

<?php echo $body ?> 