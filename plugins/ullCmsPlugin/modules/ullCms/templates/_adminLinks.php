<h3><?php echo __('Content Mangement', null, 'ullCmsMessages') ?></h3>
<ul class="tc_tasks">
  <li><?php echo ull_tc_task_link('/ullCmsThemeNGPlugin/images/ull_cms_24x24', 'ullCms/list', __('Manage', null, 'common') . ' ' . __('Pages', null, 'ullCmsMessages')) ?></li>
  <li><?php echo ullTableConfiguration::renderTaskCenterLink('UllCmsMenuItem', 'ullCms', 'ull_cms_24x24') ?></li>
</ul>