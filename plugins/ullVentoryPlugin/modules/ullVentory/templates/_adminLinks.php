<?php if (UllUserTable::hasPermission('ull_ventory_admin')): ?>
  <h3><?php echo __('Administration', null, 'ullCoreMessages') ?></h3>
  <ul class="tc_tasks">
    <li><?php echo ullTableConfiguration::renderTaskCenterLink('UllVentoryItemType') ?></li>
    <li><?php echo ullTableConfiguration::renderTaskCenterLink('UllVentoryItemManufacturer') ?></li>
    <li><?php echo ullTableConfiguration::renderTaskCenterLink('UllVentoryItemModel') ?></li>
    <li><?php echo ullTableConfiguration::renderTaskCenterLink('UllVentoryItemAttribute') ?></li>
    <li><?php echo ullTableConfiguration::renderTaskCenterLink('UllVentoryItemTypeAttribute') ?></li>
    <li><?php echo ullTableConfiguration::renderTaskCenterLink('UllVentorySoftware') ?></li>
    <li><?php echo ullTableConfiguration::renderTaskCenterLink('UllVentorySoftwareLicense') ?></li>
    <li><?php echo ullTableConfiguration::renderTaskCenterLink('UllVentoryOriginDummyUser') ?></li>
    <li><?php echo ullTableConfiguration::renderTaskCenterLink('UllVentoryStatusDummyUser') ?></li>
    <li><?php echo ullTableConfiguration::renderTaskCenterLink('UllVentoryTaking') ?></li>        
  </ul>
<?php endif ?>     