<?php echo $breadcrumbTree->getHtml(); ?>

<h1><?php echo __('ullUser Startpage'); ?></h1>

<h4><?php echo __('Links'); ?></h4>
<ul>
  <li><?php echo link_to(__('Users'), 'ullUser/list') ?></li>
  <li><?php echo link_to(__('Groups'), 'ullGroup') ?></li>
  <li><?php echo link_to(__('UserGroups'), 'ullUserGroup') ?></li>
  <li><?php echo link_to(__('Locations'), 'ullLocation') ?></li>
  <li><?php echo link_to(__('TableTool'), 'ullTableTool/list/?table=ull_user') ?></li>
</ul>
