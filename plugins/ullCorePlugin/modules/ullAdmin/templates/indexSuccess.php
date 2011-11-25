<?php echo $breadcrumb_tree ?>

<div id="tc_wrapper">
  <div id="tc_header">
    <!-- add header here -->
  </div>
  <div id="tc_container">
    <div id="tc_tasks">
      <h3><?php echo __('Actions', null, 'common') ?></h3>
      <ul class="tc_tasks">
        <li><?php echo ull_tc_task_link('/ullCoreThemeNGPlugin/images/ull_user_24x24', 'ullUser/create', __('Create user', null, 'ullCoreMessages')) ?></li>
      </ul>
      <h3><?php echo __('User and Groups') ?></h3>
      <ul class="tc_tasks">
        <?php /* temp disable because of problems. @see http://www.ullright.org/ullFlow/edit/doc/1319
        <li><?php echo ull_tc_task_link('/ullCoreThemeNGPlugin/images/ull_clone_user_24x24', 'ullCloneUser/list', __('Manage', null, 'common') . ' ' . __('Clone users', null, 'ullCoreMessages')) ?></li>
        */ ?>
        <?php if($is_master_admin): ?>
          <li><?php echo ullTableConfiguration::renderTaskCenterLink('UllGroup', 'ullCore', 'ull_group_24x24') ?></li>
          <li><?php echo ullTableConfiguration::renderTaskCenterLink('UllEntityGroup', 'ullCore', 'ull_group_24x24') ?></li>
          <li><?php echo ullTableConfiguration::renderTaskCenterLink('UllGroupPermission', 'ullCore', 'ull_permission_24x24') ?></li>
          <li><?php echo ullTableConfiguration::renderTaskCenterLink('UllPermission', 'ullCore', 'ull_permission_24x24') ?></li>
        <?php endif ?>
        <li><?php echo ull_tc_task_link('/ullCoreThemeNGPlugin/images/ull_group_membership_24x24', 'ullUser/massChangeSuperior', __('Superior mass change')) ?></li>
        <li><?php echo ull_tc_task_link('/ullCoreThemeNGPlugin/images/ull_photo_24x24', 'ullPhoto/index', __('Upload user photos', null, 'ullCoreMessages')) ?></li>
      </ul>
      <h3><?php echo __('Organizational') ?></h3>
      <ul class="tc_tasks">
        <li><?php echo ullTableConfiguration::renderTaskCenterLink('UllCompany', 'ullCore', 'ull_admin_24x24') ?></li>
        <li><?php echo ullTableConfiguration::renderTaskCenterLink('UllLocation', 'ullCore', 'ull_admin_24x24') ?></li>
        <li><?php echo ullTableConfiguration::renderTaskCenterLink('UllDepartment', 'ullCore', 'ull_admin_24x24') ?></li>
        <li><?php echo ullTableConfiguration::renderTaskCenterLink('UllJobTitle', 'ullCore', 'ull_admin_24x24') ?></li>
        <li><?php echo ullTableConfiguration::renderTaskCenterLink('UllEmploymentType', 'ullCore', 'ull_admin_24x24') ?></li>
        <li><?php echo ullTableConfiguration::renderTaskCenterLink('UllUserStatus', 'ullCore', 'ull_admin_24x24') ?></li>
      </ul>
      
      <?php if($is_master_admin): ?>
        <h3><?php echo __('Select Boxes') ?></h3>
        <ul class="tc_tasks">
          <li><?php echo ullTableConfiguration::renderTaskCenterLink('UllSelect', 'ullCore', 'ull_admin_24x24') ?></li>
          <li><?php echo ullTableConfiguration::renderTaskCenterLink('UllSelectChild', 'ullCore', 'ull_admin_24x24') ?></li>
        </ul>
        
        <?php /* Load global admin link partials fore enabled modules 
          (Defined in .../myModule/templates/_globalAdminLinks.php) */ ?>
        <?php foreach ($modules as $module): ?>
          <?php try {include_partial($module . '/globalAdminLinks');} catch (Exception $e) {} ?>
        <?php endforeach ?>
         
      <?php endif ?>          
    </div>
    
    <div id="tc_search">
      <?php echo form_tag('ullUser/list'); ?>
        <div class="tc_box color_medium_bg">
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
                <?php echo javascript_tag('document.getElementById("filter_search").focus();'); ?>
            </td>
          </tr>
          </table>
              
        </div>
        <div class="tc_box_with_bottom_spacer color_light_bg">
          <?php echo ull_link_to(__('Advanced search', null, 'common'), 'ullUser/search') ?>
        </div>
        
        <div class="tc_box_with_bottom_spacer color_medium_bg">
          <h3><?php echo $form['id']->renderLabel() ?></h3>
          <?php echo $form['id']->render() ?><?php echo submit_image_tag(ull_image_path('search'), array('class' => 'tc_search_quick_top_img')) ?>
          <dfn><?php echo $form['id']->renderHelp() ?></dfn>
        </div>      
        
        <div class="tc_search_tag_top color_medium_bg"><h3><?php echo __('By popular tags', null, 'common') ?></h3></div>
        <div class="tc_search_tag_bottom color_light_bg">
          <?php
            sfContext::getInstance()->getConfiguration()->loadHelpers(array('Tags'));
            echo tag_cloud($tags_pop, $tagurl);
          ?>      
        </div>        
      
      </form>
    </div>
    
    <div id="tc_queries">
      <div class="tc_query_box color_light_bg">
        <h3>
         <?php echo __('Queries', null, 'common') ?>
        </h3>
        <?php echo $named_queries ?>
      </div>
      
      <?php if ($named_queries_custom): ?> 
        <div class="tc_query_box color_light_bg">
          <h3>
           <?php echo __('Individual queries', null, 'common') ?>
          </h3>
          <?php echo $named_queries_custom ?>
        </div>
      <?php endif ?>
       
    </div>
  </div>
  <div id="tc_footer">
     <!-- add footer here -->
  </div>
</div>

<?php use_javascripts_for_form($form) ?>
<?php use_stylesheets_for_form($form) ?>