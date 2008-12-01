<?php echo $sf_data->getRaw('breadcrumbTree')->getHtml() ?>

<div id="tc_wrapper">
  <div id="tc_header">
    <!-- add header here -->
  </div>
  <div id="tc_container">
    <div id="tc_tasks">
      <h3>User and Groups</h3>
      <ul class="tc_tasks">
        <li><?php echo link_to(image_tag("/ullCoreThemeNGPlugin/images/ull_admin_32x32", 'alt=' . __('Manage users')), 'ullTableTool/list?table=UllUser') . link_to(__('Manage users'), 'ullTableTool/list?table=UllUser') ?></li>
        <li><?php echo link_to(image_tag("/ullCoreThemeNGPlugin/images/ull_admin_32x32", 'alt=' . __('Manage groups')), 'ullTableTool/list?table=UllGroup') . link_to( __('Manage groups'), 'ullTableTool/list?table=UllGroup') ?></li>
        <li><?php echo link_to(image_tag("/ullCoreThemeNGPlugin/images/ull_admin_32x32", 'alt=' . __('Manage group memberships')), 'ullTableTool/list?table=UllEntityGroup') . link_to(__('Manage group memberships'), 'ullTableTool/list?table=UllEntityGroup') ?></li>
      </ul>
      <h3>Select boxes</h3>
      <ul class="tc_tasks">
        <li><?php echo link_to(image_tag("/ullCoreThemeNGPlugin/images/ull_admin_32x32", 'alt=' . __('Manage select boxes')), 'ullTableTool/list?table=UllSelect') . link_to(__('Manage select boxes'), 'ullTableTool/list?table=UllSelect') ?></li>
        <li><?php echo link_to(image_tag("/ullCoreThemeNGPlugin/images/ull_admin_32x32", 'alt=' . __('Manage select box entires')), 'ullTableTool/list?table=UllSelectChild') . link_to( __('Manage select box entries'), 'ullTableTool/list?table=UllSelectChild') ?></li>
      </ul>
      <h3>Table Tool</h3>
      <ul class="tc_tasks">
        <li><?php echo link_to(image_tag("/ullCoreThemeNGPlugin/images/ull_admin_32x32", 'alt=' . __('Manage table configurations')), 'ullTableTool/list?table=UllTableConfig') . link_to(__('Manage table configurations'), 'ullTableTool/list?table=UllTableConfig') ?></li>
        <li><?php echo link_to(image_tag("/ullCoreThemeNGPlugin/images/ull_admin_32x32", 'alt=' . __('Manage column configurations')), 'ullTableTool/list?table=UllColumnConfig') . link_to( __('Manage column configurations'), 'ullTableTool/list?table=UllColumnConfig') ?></li>
        <li><?php echo link_to(image_tag("/ullCoreThemeNGPlugin/images/ull_admin_32x32", 'alt=' . __('Manage field types (ullMetaWidgets)')), 'ullTableTool/list?table=UllColumnType') . link_to( __('Manage field types (ullMetaWidgets)'), 'ullTableTool/list?table=UllColumnType') ?></li>
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