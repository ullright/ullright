<?php echo $sf_data->getRaw('breadcrumbTree')->getHtml() ?>

<div id="tc_wrapper">
  <div id="tc_header">
    <!-- add header here -->
  </div>
  <div id="tc_container">
    <div id="tc_tasks">
      <h3><?php echo __('User and Groups', null, 'common') ?></h3>
      <ul class="tc_tasks">
        <li><?php echo ull_tc_task_link('/ullCoreThemeNGPlugin/images/ull_admin_32x32', 'ullTableTool/list?table=UllUser', 'Manage users') ?></li>
        <li><?php echo ull_tc_task_link('/ullCoreThemeNGPlugin/images/ull_admin_32x32', 'ullTableTool/list?table=UllGroup', 'Manage groups') ?></li>
        <li><?php echo ull_tc_task_link('/ullCoreThemeNGPlugin/images/ull_admin_32x32', 'ullTableTool/list?table=UllPermission', 'Manage permissions') ?></li>
        <li><?php echo ull_tc_task_link('/ullCoreThemeNGPlugin/images/ull_admin_32x32', 'ullTableTool/list?table=UllEntityGroup', 'Manage group memberships') ?></li>
        <li><?php echo ull_tc_task_link('/ullCoreThemeNGPlugin/images/ull_admin_32x32', 'ullTableTool/list?table=UllGroupPermission', 'Manage group permissions') ?></li>
      </ul>
      <h3><?php echo __('Select boxes', null, 'common') ?></h3>
      <ul class="tc_tasks">
        <li><?php echo ull_tc_task_link('/ullCoreThemeNGPlugin/images/ull_admin_32x32', 'ullTableTool/list?table=UllSelect', 'Manage select boxes') ?></li>
        <li><?php echo ull_tc_task_link('/ullCoreThemeNGPlugin/images/ull_admin_32x32', 'ullTableTool/list?table=UllSelectChild', 'Manage select box entires') ?></li>
      </ul>
      <h3><?php echo __('Table Tool', null, 'common') ?></h3>
      <ul class="tc_tasks">
        <li><?php echo ull_tc_task_link('/ullCoreThemeNGPlugin/images/ull_admin_32x32', 'ullTableTool/list?table=UllTableConfig', 'Manage table configurations') ?></li>
        <li><?php echo ull_tc_task_link('/ullCoreThemeNGPlugin/images/ull_admin_32x32', 'ullTableTool/list?table=UllColumnConfig', 'Manage column configurations') ?></li>
        <li><?php echo ull_tc_task_link('/ullCoreThemeNGPlugin/images/ull_admin_32x32', 'ullTableTool/list?table=UllColumnType', 'Manage field types (ullMetaWidgets)') ?></li>
      </ul>              
    </div>
    
    <div id="tc_search">
      <div class="tc_search_quick_top color_medium_bg"><br />tba<br /></div>
      <!-- 
      <div class="tc_search_tag_top color_medium_bg"><h3>Tags</h3></div>
      <div class="tc_search_tag_bottom color_light_bg"><br /><br /><br /><br />tba<br /></div>
      -->
    </div>
    
    <div id="tc_queries">
      <div class="tc_query_box color_light_bg">
        <h3>
         <?php echo __('Queries', null, 'common') ?>
        </h3>
        <ul>
        </ul>
      </div>
      <!-- 
      <div class="tc_query_box color_light_bg">
        <h3>
         <?php echo __('Individual queries', null, 'common') ?>
        </h3>
        <ul>
          <li><?php echo 'tba' ?></li>
        </ul>
      </div>
      --> 
    </div>
  </div>
  <div id="tc_footer">
     <!-- add footer here -->
  </div>
</div>

<!-- 

<h1><?php echo __('ullAdmin Startpage'); ?></h1>

<h4><?php echo __('Administration'); ?></h4>
<ul>
  <li><?php echo link_to(__('Table Config'), 'ullTableTool/list/?table=UllTableConfig') ?></li>
  <li><?php echo link_to(__('Column Config'), 'ullTableTool/list/?table=UllColumnConfig') ?></li>
 
  <li><?php echo link_to(__('Fields'), 'ullTableTool/list/?table=ull_field') ?></li>
  <li><?php echo link_to(__('Cultures'), 'ullTableTool/list/?table=ull_culture') ?></li>
  <li><?php echo link_to(__('Select Boxes'), 'ullTableTool/list/?table=ull_select') ?></li>
  <li><?php echo link_to(__('Select Box Children'), 'ullTableTool/list/?table=ull_select_child') ?></li>
 
</ul>

<h4><?php echo __('ullFlow Administration'); ?></h4>
<ul>
  <li><?php echo link_to(__('ullFlowActions'), 'ullTableTool/list/?table=ull_flow_action') ?></li>
  <li><?php echo link_to(__('ullFlowActionsForSteps'), 'ullTableTool/list/?table=ull_flow_step_action') ?></li>
  <li><?php echo link_to(__('ullFlowApps'), 'ullTableTool/list/?table=ull_flow_app') ?></li>
  <li><?php echo link_to(__('ullFlowDocs'), 'ullTableTool/list/?table=ull_flow_doc') ?></li>
  <li><?php echo link_to(__('ullFlowFields'), 'ullTableTool/list/?table=ull_flow_field') ?></li>
  <li><?php echo link_to(__('ullFlowMemories'), 'ullTableTool/list/?table=ull_flow_memory') ?></li>
  <li><?php echo link_to(__('ullFlowSteps'), 'ullTableTool/list/?table=ull_flow_step') ?></li>  
  <li><?php echo link_to(__('ullFlowValues'), 'ullTableTool/list/?table=ull_flow_value') ?></li>
</ul>

<h4><?php echo __('System'); ?></h4>
<ul>
  <li><?php echo link_to(__('ull_access_group'), 'ullTableTool/list/?table=ull_access_group') ?></li>
  <li><?php echo link_to(__('ull_access_group_group'), 'ullTableTool/list/?table=ull_access_group_group') ?></li>
</ul>
//-->