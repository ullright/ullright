<h3><?php echo __('Administration', null, 'ullCoreMessages') ?></h3>
<ul class="tc_tasks">
  <?php if (ullCoreTools::isModuleEnabled('ullNews')): ?>
    <li><?php echo ull_tc_task_link('/ullCmsThemeNGPlugin/images/action_icons/admin_24x24', 'ullNews/list', __('Manage', null, 'common') . ' ' . __('News entries', null, 'ullNewsMessages')) ?></li>
  <?php endif ?>
  <li><?php echo ullTableConfiguration::renderTaskCenterLink('UllCmsMenuItem') ?></li>
  <li><?php echo ull_tc_task_link('/ullCmsThemeNGPlugin/images/action_icons/admin_24x24', 'ullCmsContentBlock/list', __('Manage', null, 'common') . ' ' . __('Content blocks', null, 'ullCmsMessages')) ?></li>
  <li><?php echo ullTableConfiguration::renderTaskCenterLink('UllCmsContentType') ?></li>
</ul>
