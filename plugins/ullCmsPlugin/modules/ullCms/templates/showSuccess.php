<?php slot('sidebar') ?>
  <div id="sidebar_ull_cms">
    <?php echo $sidebar_menu ?>
  </div>
<?php end_slot() ?>

<h1>
  <?php if ($allow_edit): ?>
    <?php
      echo ull_link_to(ull_image_tag('edit'),
        array('module' => 'ullCms', 'action' => 'edit', 'id' => $doc->id));
    ?>              
  <?php endif ?>
  <?php echo $doc->title ?>
</h1>

<?php echo $doc->getBody(ESC_RAW) ?> 