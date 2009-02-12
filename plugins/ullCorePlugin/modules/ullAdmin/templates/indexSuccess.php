<?php echo $sf_data->getRaw('breadcrumbTree')->getHtml() ?>

<div id="tc_wrapper">
  <div id="tc_header">
    <!-- add header here -->
  </div>
  <div id="tc_container">
    <div id="tc_tasks">
      <h3><?php echo __('User and Groups') ?></h3>
      <ul class="tc_tasks">
        <li><?php echo ull_tc_task_link('/ullCoreThemeNGPlugin/images/ull_user_24x24', 'ullTableTool/list?table=UllUser', __('Manage users')) ?></li>
        <li><?php echo ull_tc_task_link('/ullCoreThemeNGPlugin/images/ull_group_24x24', 'ullTableTool/list?table=UllGroup', __('Manage groups')) ?></li>
        <li><?php echo ull_tc_task_link('/ullCoreThemeNGPlugin/images/ull_permission_24x24', 'ullTableTool/list?table=UllPermission', __('Manage permissions')) ?></li>
        <li><?php echo ull_tc_task_link('/ullCoreThemeNGPlugin/images/ull_group_24x24', 'ullTableTool/list?table=UllEntityGroup', __('Manage group memberships')) ?></li>
        <li><?php echo ull_tc_task_link('/ullCoreThemeNGPlugin/images/ull_permission_24x24', 'ullTableTool/list?table=UllGroupPermission', __('Manage group permissions')) ?></li>
        <!-- <li><?php echo ull_tc_task_link('/ullCoreThemeNGPlugin/images/ull_admin_24x24', 'ullUser/massChangeSuperior', __('Superior mass change')) ?></li> -->
      </ul>
      <h3><?php echo __('Organizational') ?></h3>
      <ul class="tc_tasks">
        <li><?php echo ull_tc_task_link('/ullCoreThemeNGPlugin/images/ull_admin_24x24', 'ullTableTool/list?table=UllCompany', __('Manage companies')) ?></li>
        <li><?php echo ull_tc_task_link('/ullCoreThemeNGPlugin/images/ull_admin_24x24', 'ullTableTool/list?table=UllDepartment', __('Manage departments')) ?></li>
        <li><?php echo ull_tc_task_link('/ullCoreThemeNGPlugin/images/ull_admin_24x24', 'ullTableTool/list?table=UllLocation', __('Manage locations')) ?></li>
        <li><?php echo ull_tc_task_link('/ullCoreThemeNGPlugin/images/ull_admin_24x24', 'ullTableTool/list?table=UllEmploymentType', __('Manage employment types')) ?></li>
        <li><?php echo ull_tc_task_link('/ullCoreThemeNGPlugin/images/ull_admin_24x24', 'ullTableTool/list?table=UllJobTitle', __('Manage job titles')) ?></li>
      </ul>
      <h3><?php echo __('Select Boxes') ?></h3>
      <ul class="tc_tasks">
        <li><?php echo ull_tc_task_link('/ullCoreThemeNGPlugin/images/ull_admin_24x24', 'ullTableTool/list?table=UllSelect', __('Manage select boxes')) ?></li>
        <li><?php echo ull_tc_task_link('/ullCoreThemeNGPlugin/images/ull_admin_24x24', 'ullTableTool/list?table=UllSelectChild', __('Manage select box entries')) ?></li>
      </ul>
      <h3><?php echo __('Table Administration') ?></h3>
      <ul class="tc_tasks">
        <li><?php echo ull_tc_task_link('/ullCoreThemeNGPlugin/images/ull_admin_24x24', 'ullTableTool/list?table=UllTableConfig', __('Manage table configurations')) ?></li>
        <li><?php echo ull_tc_task_link('/ullCoreThemeNGPlugin/images/ull_admin_24x24', 'ullTableTool/list?table=UllColumnConfig', __('Manage column configurations')) ?></li>
        <li><?php echo ull_tc_task_link('/ullCoreThemeNGPlugin/images/ull_admin_24x24', 'ullTableTool/list?table=UllColumnType', __('Manage field types (ullMetaWidgets)')) ?></li>
      </ul> 
      <h3><?php echo __('Workflow') ?></h3>
      <ul class="tc_tasks">
	      <li><?php echo ull_tc_task_link('/ullFlowThemeNGPlugin/images/ull_flow_24x24', 'ullTableTool/list?table=UllFlowApp', __('Manage applications')) ?></li>
	      <li><?php echo ull_tc_task_link('/ullFlowThemeNGPlugin/images/ull_flow_24x24', 'ullTableTool/list?table=UllFlowAppPermission', __('Manage application access rights')) ?></li>
	      <li><?php echo ull_tc_task_link('/ullFlowThemeNGPlugin/images/ull_flow_24x24', 'ullTableTool/list?table=UllFlowColumnConfig', __('Manage columns')) ?></li>
	      <li><?php echo ull_tc_task_link('/ullFlowThemeNGPlugin/images/ull_flow_24x24', 'ullTableTool/list?table=UllFlowStep', __('Manage steps')) ?></li>
	      <li><?php echo ull_tc_task_link('/ullFlowThemeNGPlugin/images/ull_flow_24x24', 'ullTableTool/list?table=UllFlowStepAction', __('Manage actions for steps')) ?></li>
	      <li><?php echo ull_tc_task_link('/ullFlowThemeNGPlugin/images/ull_flow_24x24', 'ullTableTool/list?table=UllFlowAction', __('Manage actions')) ?></li>  
	    </ul>             
    </div>
    
    <div id="tc_search">
      <div class="tc_search_quick_top color_medium_bg">
        <?php echo form_tag('ullTableTool/list?table=UllUser'); ?>
        <h3><?php echo __('Search for Users') ?></h3>
        <?php echo $form['search']->render() ?>
        <?php echo submit_image_tag(ull_image_path('search', null, null, 'ullCore'),
            array('class' => 'tc_search_quick_top_img')) ?>
        </form>      
      </div>
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
        <!-- empty ul is not xhtml compliant 
        <ul>
        </ul>
        -->
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