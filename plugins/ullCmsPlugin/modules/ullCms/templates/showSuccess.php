<?php slot('sidebar') ?>
<div id="sidebar_ull_cms">
  <?php echo $sidebar_menu ?>
</div>
<?php end_slot() ?>

<?php echo $main_menu ?>

<h3><?php echo $doc->title ?></h3>

<?php echo $doc->getBody(ESC_RAW) ?> 