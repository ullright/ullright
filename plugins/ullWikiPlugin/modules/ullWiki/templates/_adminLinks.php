<?php if (UllUserTable::hasPermission('ull_wiki_admin')): ?>
  <h3><?php echo __('Administration', null, 'ullCoreMessages') ?></h3>
  <ul class="tc_tasks">
    <li><?php echo ullTableConfiguration::renderTaskCenterLink('UllWikiAccessLevel') ?></li>
    <li><?php echo ullTableConfiguration::renderTaskCenterLink('UllWikiAccessLevelAccess') ?></li>        
  </ul>
<?php endif ?>    