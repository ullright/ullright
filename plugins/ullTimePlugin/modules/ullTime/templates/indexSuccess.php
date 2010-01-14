<?php echo $sf_data->getRaw('breadcrumbTree')->getHtml();?>

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
      
      <h3><?php echo __('Period overviews', null, 'ullTimeMessages') ?></h3>
      <ul class="tc_tasks">
        <?php foreach ($periods as $period): ?>
          <li><?php echo ull_tc_task_link('/ullTimeThemeNGPlugin/images/action_icons/create_24x24', array('action' => 'list', 'period' => $period->slug), $period->name) ?></li>
        <?php endforeach ?>
      </ul>      
    </div>
    
    <div id="tc_search">
    
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
         <?php echo __('Queries', null, 'common') ?>
        </h3>
        <?php //echo $named_queries->renderList(ESC_RAW) ?>
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