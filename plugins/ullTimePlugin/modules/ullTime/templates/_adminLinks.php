<?php if (UllUserTable::hasPermission('ull_time_admin')): ?>
  <h3><?php echo __('Administration', null, 'ullCoreMessages') ?></h3>
  <ul class="tc_tasks">
    <li><?php echo ullTableConfiguration::renderTaskCenterLink('UllProject') ?></li>
    <li><?php echo ullTableConfiguration::renderTaskCenterLink('UllProjectManager') ?></li>
    <li><?php echo ullTableConfiguration::renderTaskCenterLink('UllTimePeriod') ?></li>
  </ul>
<?php endif ?>  