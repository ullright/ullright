<?php echo $breadcrumb_tree ?>

<div id="tc_wrapper">
  <div id="tc_header">
    <!-- add header here -->
  </div>
  <div id="tc_container">
    <div id="tc_tasks">
      <h3>
        <?php if ($app): ?>
          <?php echo __('Actions', null, 'common') ?>	
        <?php else: ?>
          <?php echo __('Workflows', null, 'ullFlowMessages') ?>
        <?php endif ?>
      </h3>
      
    <ul class="tc_tasks">
			<?php if ($app): ?>
			
        <li>
			   <?php echo ull_tc_task_link('/ullFlowThemeNGPlugin/images/action_icons/create_24x24',
            array('action' => 'create'), __('Create %1%', array('%1%' => $app->doc_label)))?>
        </li>

			 <?php else: ?>
			 
			   <?php foreach ($apps as $current_app): ?>
          <li>
            <?php echo ull_tc_task_link($current_app->getIconPath(24, 24),
            'ullFlow/index?app=' . $current_app->slug, $current_app->label) ?>
          </li>
          <?php endforeach ?>
          
        <?php endif ?>
     </ul>
     
     <?php include_partial('ullFlow/adminLinks')?>
     
    </div>
    
    <div id="tc_search">
      <div class="tc_box color_medium_bg">
        <?php echo ull_form_tag(array('action' => 'list')); ?>
          <?php echo $form['search']->renderLabel() ?><br />    
          <?php echo $form['search']->render() ?>
          <?php echo submit_image_tag(ull_image_path('search'),
              array('class' => 'tc_search_quick_top_img')) ?>
          <?php echo javascript_tag('document.getElementById("filter_search").focus();'); ?>
        </form>
      </div>
      
       
      <div class="tc_box_with_bottom_spacer color_light_bg">
           <?php
              if ($app)
              {
                echo ull_link_to(__('Advanced search', null, 'common'), 'ullFlow/search?app=' . $app->slug);
              }
              else
              {
                echo ull_link_to(__('Advanced search', null, 'common'), 'ullFlow/search');
              }
            ?>
      </div>
      
      <div class="tc_search_tag_top color_medium_bg"><h3><?php echo __('By popular tags', null, 'common') ?></h3></div>
      <div class="tc_search_tag_bottom color_light_bg">
        <?php
          sfContext::getInstance()->getConfiguration()->loadHelpers(array('Tags'));
          echo tag_cloud($tags_pop, 'ullFlow/list?filter[search]=%s' . (($app) ? '&app=' . $app->slug : ''));
        ?>
      </div>
    </div>
    
    <div id="tc_queries">
      <div class="tc_query_box color_light_bg">
        <h3><?php use_javascripts_for_form($form) ?>
<?php use_stylesheets_for_form($form) ?>
         <?php echo __('Queries', null, 'common') ?>
        </h3>
        <?php echo $named_queries->renderList(ESC_RAW) ?>
        <ul>
          <li>
            <?php echo link_to(__('Assignment overview', null, 'ullFlowMessages'), 
              'ullFlow/assignmentOverview' . (($app) ? '?app=' . $app->slug : '')) ?>
          </li>
        </ul>
        
      </div>
      <?php if ($named_queries_custom): ?>
        <div class="tc_query_box color_light_bg">
        <h3>
          <?php echo __('Individual queries', null, 'common') ?>
        </h3>
        <?php echo $named_queries_custom->renderList(ESC_RAW) ?>
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