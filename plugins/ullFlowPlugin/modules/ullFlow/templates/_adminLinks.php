<?php if (UllUserTable::hasPermission('ull_flow_admin')): ?>
  <h3><?php echo __('Administration', null, 'ullCoreMessages') ?></h3>
  <ul class="tc_tasks">
    <li><?php echo ullTableConfiguration::renderTaskCenterLink('UllFlowApp') ?></li>
    <li><?php echo ullTableConfiguration::renderTaskCenterLink('UllFlowAppAccess') ?></li>
    <li><?php echo ullTableConfiguration::renderTaskCenterLink('UllFlowColumnConfig') ?></li>
    <li><?php echo ullTableConfiguration::renderTaskCenterLink('UllFlowStep') ?></li>
    <li><?php echo ullTableConfiguration::renderTaskCenterLink('UllFlowStepAction') ?></li>
    <li><?php echo ullTableConfiguration::renderTaskCenterLink('UllFlowAction') ?></li>       
  </ul>
<?php endif ?>      