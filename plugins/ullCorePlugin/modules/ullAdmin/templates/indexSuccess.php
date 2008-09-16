<?php echo $sf_data->getRaw('breadcrumbTree')->getHtml() ?>

<h1><?php echo __('ullAdmin Startpage'); ?></h1>

<h4><?php echo __('Links'); ?></h4>
<ul>
  <li><?php echo link_to(__('Manage users'), 'ullTableTool/list/?table=ullUser') ?></li>
  <li><?php echo link_to(__('Manage groups'), 'ullTableTool/list/?table=ullGroup') ?></li>
  <li><?php echo link_to(__('Manage group memberships'), 'ullTableTool/list/?table=ullEnityGroup') ?></li>
  <!-- <li><?php echo link_to(__('Locations'), 'ullTableTool/list/?table=ull_location') ?></li> -->
</ul>

<h4><?php echo __('Administration'); ?></h4>
<ul>
  <li><?php echo link_to(__('Table Config'), 'ullTableTool/list/?table=ullTableConfig') ?></li>
  <li><?php echo link_to(__('Column Config'), 'ullTableTool/list/?table=ullColumnConfig') ?></li>
<!--   
  <li><?php echo link_to(__('Fields'), 'ullTableTool/list/?table=ull_field') ?></li>
  <li><?php echo link_to(__('Cultures'), 'ullTableTool/list/?table=ull_culture') ?></li>
  <li><?php echo link_to(__('Select Boxes'), 'ullTableTool/list/?table=ull_select') ?></li>
  <li><?php echo link_to(__('Select Box Children'), 'ullTableTool/list/?table=ull_select_child') ?></li>
//-->  
</ul>

<!--
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