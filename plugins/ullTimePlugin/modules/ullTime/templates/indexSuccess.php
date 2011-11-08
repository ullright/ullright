<?php echo $breadcrumb_tree ?>

<?php include_partial('ullTableTool/globalError', array('form' => $act_as_user_form)) ?>

<div id="tc_wrapper">
  <div id="tc_header">
    <!-- add header here -->
  </div>
  <div id="tc_container">
    <div id="tc_tasks">
      <h3><?php echo __('Actions', null, 'common') ?></h3>
      <ul class="tc_tasks">
        <li><?php echo ull_tc_task_link('/ullTimeThemeNGPlugin/images/action_icons/create_24x24', array('action' => 'create'), __('Timereporting for today', null, 'ullTimeMessages')) ?></li>
        <li><?php echo ull_tc_task_link('/ullTimeThemeNGPlugin/images/action_icons/create_24x24', array('action' => 'createProject'), __('Project timereporting for today', null, 'ullTimeMessages')) ?></li>
      </ul>
      
      <?php if (UllUserTable::hasPermission('ull_time_act_as_user')): ?>
        <h3><?php echo __('Act as user', null, 'ullTimeMessages') ?></h3>
        <?php ?>
        <?php echo form_tag('ullTime/index'); ?>
        <ul class="tc_tasks">
          <li><?php echo $act_as_user_form['ull_user_id']->render() ?><?php echo $act_as_user_form['ull_user_id']->renderError() ?></li>
        </ul>
        </form>
      <?php endif ?>      
      
      
      <?php if (isset($future_periods)): ?>
        <h3><?php echo __('Future periods', null, 'ullTimeMessages') ?></h3>
        <ul class="tc_tasks" id="ull_time_index_future_periods">
          <?php foreach ($future_periods as $future_period): ?>
            <li><?php echo ull_tc_task_link(
              '/ullTimeThemeNGPlugin/images/action_icons/create_24x24', 
              array('action' => 'list', 'period' => $future_period->slug), 
              $future_period->name) ?></li>
          <?php endforeach ?>
        </ul>
      <?php endif ?>              
      
      
      <h3><?php echo __('Period overviews', null, 'ullTimeMessages') ?></h3>
      <ul class="tc_tasks">
        <?php $old_year = date('Y') ?>
        <?php foreach ($periods as $period): ?>
          <?php $year = substr($period->from_date, 0, 4) ?>
          <?php if ($year != $old_year): ?>
            <li><h3><?php echo $year ?></h3></li>
            <?php $old_year = $year; ?>
          <?php endif ?>            
          <li><?php echo ull_tc_task_link('/ullTimeThemeNGPlugin/images/action_icons/create_24x24', array('action' => 'list', 'period' => $period->slug), $period->name) ?></li>
        <?php endforeach ?>
      </ul>   
      
      
      <?php if (UllUserTable::hasPermission('ull_time_admin')): ?>
        <h3><?php echo __('Administration', null, 'ullCoreMessages') ?></h3>
        <ul class="tc_tasks">
          <li><?php echo ullTableConfiguration::renderTaskCenterLink('UllProject') ?></li>
          <li><?php echo ullTableConfiguration::renderTaskCenterLink('UllProjectManager') ?></li>
          <li><?php echo ullTableConfiguration::renderTaskCenterLink('UllTimePeriod') ?></li>
        </ul>
      <?php endif ?>         
      
         
    </div>
    
    <div id="tc_search">
      &nbsp;
    <!-- 
    <?php //echo ull_form_tag(array('action' => 'list')); ?>
      <div class="tc_box color_medium_bg">
        <?php //echo $form['search']->renderLabel() ?><br />    
        <?php //echo $form['search']->render() ?><?php echo submit_image_tag(ull_image_path('search'), array('class' => 'tc_search_quick_top_img')) ?>
      </div>
      <div class="tc_box_with_bottom_spacer color_light_bg">
        <?php //echo ull_link_to(__('Advanced search', null, 'common'), 'ullTime/search') ?>
      </div>
      <div class="tc_box_with_bottom_spacer color_medium_bg">
        <?php //echo $form['ull_entity_id']->renderLabel() ?><br />
        <?php //echo $form['ull_entity_id']->render() ?><?php echo submit_image_tag(ull_image_path('search'), array('class' => 'tc_search_quick_top_img')) ?>
      </div>
      </form>
    -->

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
         <?php echo __('Project reports', null, 'ullTimeMessages') ?>
        </h3>
        <ul>
          <li><?php echo link_to(__('My projects', null, 'ullTimeMessages'), 'ullTime/reportProject?report=by_project')?></li>
          <li><?php echo link_to(__('My team', null, 'ullTimeMessages'), 'ullTime/reportProject?report=by_user')?></li>
          <!-- <li><?php // echo link_to(__('Details', null, 'ullTimeMessages'), 'ullTime/reportProject?report=details')?></li>  -->
        </ul>
      </div>
      
      
      <!-- 
      <div class="tc_query_box color_light_bg">
        <h3>
         <?php //echo __('Individual queries', null, 'common') ?>
        </h3>
        <ul>
          <li><?php //echo 'tba' ?></li>
        </ul>
      </div>
      --> 
    </div>
  </div>
  <div id="tc_footer">
     <!-- add footer here -->
  </div>
</div>

<?php echo javascript_tag('

/*
 * Hide the future periods and display a link instead
 */
$(document).ready(function() 
{
  $("#ull_time_index_future_periods").children().hide();

  $("#ull_time_index_future_periods").prepend(
    "<li id=\"ull_time_show_future_periods_message\"><a href=\"#\" onclick=\"showFuturePeriods(); return false;\">' . __('Show', null, 'ullTimeMessages') . '</a></li>"
  );
  
});

/*
 * Unhide future periods
 */ 
function showFuturePeriods()
{
  $("#ull_time_index_future_periods").children().fadeIn(500);
  document.getElementById("ull_time_show_future_periods_message").style.display = "none"; 
}

') ?>

<?php use_javascripts_for_form($act_as_user_form) ?>
<?php use_stylesheets_for_form($act_as_user_form) ?>