<?php slot('sidebar') ?>
<div id="sidebar_ull_cms">
  <?php echo $sidebar_navigation ?>
</div>
<?php end_slot() ?>


<h3><?php echo $doc->title ?></h3>

<?php echo $doc->getBody(ESC_RAW) ?> 