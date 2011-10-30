<?php echo $breadcrumb_tree ?>

<h3><?php echo __('Please select the content block type you wish to create', null, 'ullCmsMessages') ?>:</h3>

<ul>
  <?php foreach ($types as $type): ?>
    <li>
      <?php echo link_to($type->name, 'ullCmsContentBlock/create?content_type=' . $type->slug) ?>
    </li>
    
  <?php endforeach ?>
</ul>