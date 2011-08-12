<?php echo $breadcrumb_tree ?>

<div id="tc_wrapper">
  <div id="tc_header">
    <!-- add header here -->
  </div>
  <div id="tc_container">
  
  
    <div id="tc_tasks">
      <h3><?php echo __('Actions', null, 'common') ?></h3>
      <ul class="tc_tasks">
        <li>
          <?php echo ull_tc_task_link(
            '/ullCoreThemeNGPlugin/images/action_icons/create_24x24', 
            'ullCourse/create', 
            __('Create course', null, 'ullCourseMessages')) ?>
        </li>
        <li>
          <?php echo ull_tc_task_link(
            '/ullCoreThemeNGPlugin/images/action_icons/create_24x24', 
            'ullCourseBooking/create', 
            __('Create booking', null, 'ullCourseMessages')) ?>
        </li>        
      </ul>
      
      
      <h3><?php echo __('Administration', null, 'common') ?></h3>
      <ul class="tc_tasks">
        <li>
          <?php echo ull_tc_task_link(
            '/ullCoreThemeNGPlugin/images/ull_user_24x24', 
            'ullTableTool/list?table=UllEntityGroup&filter[ull_group_id]=' .
              UllGroupTable::findIdByDisplayName('Trainers'), 
            __('Manage trainers', null, 'ullCourseMessages')) ?>
        </li> 
        <li>
          <?php echo ull_tc_task_link(
            '/ullCoreThemeNGPlugin/images/ull_admin_24x24.png', 
            'ullTableTool/list?table=UllCourseTariff',
            __('Manage tariffs', null, 'ullCourseMessages')) ?>
        </li> 
      </ul>      

    </div>    
    
    
    <div id="tc_search">
    
    <?php echo ull_form_tag(array('action' => 'list')); ?>
      <div class="tc_box_with_bottom_spacer color_medium_bg">
        <h3><?php echo __('Search courses', null, 'ullCourseMessages') ?></h3>    
        <?php echo $form['search']->render() ?><?php echo submit_image_tag(
          ull_image_path('search'), 
          array('class' => 'tc_search_quick_top_img')) ?>
      </div>
    </form>
      
    <?php echo ull_form_tag(array('module' => 'ullCourseBooking', 'action' => 'list')); ?>
      <div class="tc_box_with_bottom_spacer color_medium_bg">
        <h3><?php echo __('Search bookings', null, 'ullCourseMessages') ?></h3>    
        <?php echo $form['search']->render(array('id' => 'search_bookings')) ?><?php echo submit_image_tag(
          ull_image_path('search'), 
          array('class' => 'tc_search_quick_top_img')) ?>
        <?php echo javascript_tag('document.getElementById("search_bookings").focus();'); ?>
      </div>
            
      <!-- 
      <div class="tc_box_with_bottom_spacer color_light_bg">
        <?php //echo ull_link_to(__('Advanced search', null, 'common'), 'ullNewsletter/search') ?>
      </div>
       -->
      <!--
      <div class="tc_box_with_bottom_spacer color_medium_bg">
        <?php //echo $form['ull_entity_id']->renderLabel() ?><br />
        <?php //echo $form['ull_entity_id']->render() ?><?php echo submit_image_tag(ull_image_path('search'), array('class' => 'tc_search_quick_top_img')) ?>
      </div>
      -->
      </form>

      <!-- 
      <div class="tc_search_quick_bottom color_light_bg"><br /><br /><br /><br />tba<br /></div>
      
      <div class="tc_search_tag_top color_medium_bg"><h3><?php echo __('By popular tags', null, 'common') ?></h3></div>
      <div class="tc_search_tag_bottom color_light_bg">
        &nbsp;   
      </div>
      
      -->
    </div>
    
    <div id="tc_queries">
      <div class="tc_query_box color_light_bg">
        <h3>
         <?php echo __('Queries', null, 'common') ?>
        </h3>
        <?php echo $named_queries_course->renderList(ESC_RAW) ?>
        <?php echo $named_queries_booking->renderList(ESC_RAW) ?>
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

<?php // use_javascripts_for_form($form) ?>
<?php // use_stylesheets_for_form($form) ?>