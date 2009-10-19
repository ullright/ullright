<?php echo $sf_data->getRaw('breadcrumbTree')->getHtml() ?>

<div id="tc_wrapper">
  <div id="tc_header">
    <!-- add header here -->
  </div>
  <div id="tc_container">
    <div id="tc_tasks">
      <h3><?php echo __('User and Groups') ?></h3>
      <ul class="tc_tasks">
        <li><?php echo ullTableConfiguration::renderTaskCenterLink('UllUser', 'ullCore', 'ull_user_24x24') ?></li>
        <li><?php echo ullTableConfiguration::renderTaskCenterLink('UllGroup', 'ullCore', 'ull_group_24x24') ?></li>
        <li><?php echo ullTableConfiguration::renderTaskCenterLink('UllPermission', 'ullCore', 'ull_permission_24x24') ?></li>
        <li><?php echo ullTableConfiguration::renderTaskCenterLink('UllEntityGroup', 'ullCore', 'ull_group_24x24') ?></li>
        <li><?php echo ullTableConfiguration::renderTaskCenterLink('UllGroupPermission', 'ullCore', 'ull_permission_24x24') ?></li>
        <li><?php echo ull_tc_task_link('/ullCoreThemeNGPlugin/images/ull_group_24x24', 'ullUser/massChangeSuperior', __('Superior mass change')) ?></li>
        <li><?php echo ull_tc_task_link('/ullCoreThemeNGPlugin/images/ull_photo_24x24', 'ullPhoto/index', __('Upload user photos', null, 'ullCoreMessages')) ?></li>
      </ul>
      <h3><?php echo __('Organizational') ?></h3>
      <ul class="tc_tasks">
        <li><?php echo ullTableConfiguration::renderTaskCenterLink('UllCompany', 'ullCore', 'ull_admin_24x24') ?></li>
        <li><?php echo ullTableConfiguration::renderTaskCenterLink('UllDepartment', 'ullCore', 'ull_admin_24x24') ?></li>
        <li><?php echo ullTableConfiguration::renderTaskCenterLink('UllLocation', 'ullCore', 'ull_admin_24x24') ?></li>
        <li><?php echo ullTableConfiguration::renderTaskCenterLink('UllEmploymentType', 'ullCore', 'ull_admin_24x24') ?></li>
        <li><?php echo ullTableConfiguration::renderTaskCenterLink('UllJobTitle', 'ullCore', 'ull_admin_24x24') ?></li>
      </ul>
      <h3><?php echo __('Select Boxes') ?></h3>
      <ul class="tc_tasks">
        <li><?php echo ullTableConfiguration::renderTaskCenterLink('UllSelect', 'ullCore', 'ull_admin_24x24') ?></li>
        <li><?php echo ullTableConfiguration::renderTaskCenterLink('UllSelectChild', 'ullCore', 'ull_admin_24x24') ?></li>
      </ul>
      <h3><?php echo __('Table Administration') ?></h3>
      <ul class="tc_tasks">
        <li><?php echo ullTableConfiguration::renderTaskCenterLink('UllColumnType', 'ullCore', 'ull_admin_24x24', __('Manage field types (ullMetaWidgets)')) ?></li>
      </ul> 
      <h3><?php echo __('Workflow') ?></h3>
      <ul class="tc_tasks">
	      <li><?php echo ullTableConfiguration::renderTaskCenterLink('UllFlowApp', 'ullFlow', 'ull_flow_24x24') ?></li>
	      <li><?php echo ullTableConfiguration::renderTaskCenterLink('UllFlowAppPermission', 'ullFlow', 'ull_flow_24x24') ?></li>
	      <li><?php echo ullTableConfiguration::renderTaskCenterLink('UllFlowColumnConfig', 'ullFlow', 'ull_flow_24x24') ?></li>
	      <li><?php echo ullTableConfiguration::renderTaskCenterLink('UllFlowStep', 'ullFlow', 'ull_flow_24x24') ?></li>
	      <li><?php echo ullTableConfiguration::renderTaskCenterLink('UllFlowStepAction', 'ullFlow', 'ull_flow_24x24') ?></li>
	      <li><?php echo ullTableConfiguration::renderTaskCenterLink('UllFlowAction', 'ullFlow', 'ull_flow_24x24') ?></li>  
	    </ul>
      <h3><?php echo __('Wiki') ?></h3>
      <ul class="tc_tasks">
        <li><?php echo ullTableConfiguration::renderTaskCenterLink('UllWikiAccessLevel', 'ullWiki', 'ull_wiki_24x24') ?></li>
        <li><?php echo ullTableConfiguration::renderTaskCenterLink('UllWikiAccessLevelAccess', 'ullWiki', 'ull_wiki_24x24') ?></li>
      </ul>          
      <h3><?php echo __('Inventory', null, 'ullVentoryMessages') ?></h3>
      <ul class="tc_tasks">
        <li><?php echo ullTableConfiguration::renderTaskCenterLink('UllVentoryItemType', 'ullVentory', 'ull_ventory_24x24') ?></li>
        <li><?php echo ullTableConfiguration::renderTaskCenterLink('UllVentoryItemManufacturer', 'ullVentory', 'ull_ventory_24x24') ?></li>
        <li><?php echo ullTableConfiguration::renderTaskCenterLink('UllVentoryItemModel', 'ullVentory', 'ull_ventory_24x24') ?></li>
        <li><?php echo ullTableConfiguration::renderTaskCenterLink('UllVentoryItemAttribute', 'ullVentory', 'ull_ventory_24x24') ?></li>
        <li><?php echo ullTableConfiguration::renderTaskCenterLink('UllVentoryItemTypeAttribute', 'ullVentory', 'ull_ventory_24x24') ?></li>
        <li><?php echo ullTableConfiguration::renderTaskCenterLink('UllVentorySoftware', 'ullVentory', 'ull_ventory_24x24') ?></li>
        <li><?php echo ullTableConfiguration::renderTaskCenterLink('UllVentorySoftwareLicense', 'ullVentory', 'ull_ventory_24x24') ?></li>
        <li><?php echo ullTableConfiguration::renderTaskCenterLink('UllVentoryOriginDummyUser', 'ullVentory', 'ull_ventory_24x24') ?></li>
        <li><?php echo ullTableConfiguration::renderTaskCenterLink('UllVentoryStatusDummyUser', 'ullVentory', 'ull_ventory_24x24') ?></li>
        <li><?php echo ullTableConfiguration::renderTaskCenterLink('UllVentoryTaking', 'ullVentory', 'ull_ventory_24x24') ?></li>
      </ul>
      <h3><?php echo __('Timereporting', null, 'ullTimeMessages') ?></h3>
      <ul class="tc_tasks">
        <li><?php echo ullTableConfiguration::renderTaskCenterLink('UllProject', 'ullTime', 'ull_time_24x24') ?></li>
        <li><?php echo ullTableConfiguration::renderTaskCenterLink('UllTimePeriod', 'ullTime', 'ull_time_24x24') ?></li>
      </ul>           
    </div>
    
    <div id="tc_search">
      <div class="tc_box color_medium_bg">
        <?php echo form_tag('ullTableTool/list?table=UllUser'); ?>
        <table>
          <tr>
            <td>
              <h3><?php echo __('Search for Users') ?></h3>
            </td>
          </tr>
          <tr>
            <td>
              <?php echo $form['search']->render() ?>
            </td>
            <td>
              <?php echo submit_image_tag(ull_image_path('search', null, null, 'ullCore'),
                array('class' => 'tc_search_quick_top_img')) ?>
            </td>
          </tr>
          </table>
        </form>      
      </div>
      <div class="tc_box_with_bottom_spacer color_light_bg">
        <?php echo ull_link_to(__('Advanced search', null, 'common'), 'ullUser/search') ?>
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