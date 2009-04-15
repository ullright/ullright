
<?php echo $sf_data->getRaw('breadcrumbTree')->getHtml() ?>
<!-- 
<?php if ($app_slug): ?>
  <h1><?php echo __('Application') . ' ' . $app->label?></h1>
<?php else: ?>
  <h1><?php echo __('Workflows') . ' ' . __('Home') ?></h1>
 <?php endif ?>
 -->
<div id="tc_wrapper">
  <div id="tc_header">
    <!-- add header here -->
  </div>
  <div id="tc_container">
    <div id="tc_tasks">
      <?php
        if ($app_slug)
        {
          echo '<h3>' . __('Actions', null, 'common') . '</h3>';	
        }
        else
        {
          echo '<h3>' . __('Applications', null, 'common') . '</h3>';
        }
      ?>

      <ul class="tc_tasks">
			<?php if ($app_slug): ?>
			
			<li><?php echo ull_tc_task_link('/ullFlowThemeNGPlugin/images/action_icons/create_24x24',
            array('action' => 'create'), __('Create %1%', array('%1%' => $app->doc_label)))
           ?></li>

			 <?php else:
			   foreach ($apps as $app): ?>
          <li><?php echo ull_tc_task_link($app->getIconPath(24, 24),
            'ullFlow/index?app=' . $app->slug, $app->label) ?></li>
        <?php endforeach; ?>
     <?php endif ?>
     </ul>
    </div>
    
    <div id="tc_search">
      <div class="tc_search_quick_top color_medium_bg">
        <?php echo ull_form_tag(array('action' => 'list')); ?>
          <table>
            <tr>
            <td><?php echo $form['search']->renderLabel() ?></td>    
            <td><?php echo $form['search']->render() ?></td>
            <td><?php echo submit_image_tag(ull_image_path('search', null, null, 'ullFlow'),
              array('class' => 'tc_search_quick_top_img')) ?></td>
          </tr>
          </table>
          </form>
         </div>
      <!-- 
      <div class="tc_search_quick_bottom color_light_bg"><br /><br /><br /><br />tba<br /></div>
      -->
      <div class="tc_search_tag_top color_medium_bg"><h3><?php echo __('By popular tags', null, 'common') ?></h3></div>
      <div class="tc_search_tag_bottom color_light_bg">
        <?php
          sfLoader::loadHelpers(array('Tags'));
          echo tag_cloud($tags_pop, 'ullFlow/list?filter[search]=%s' . ($app_slug ? '&app=' . $app_slug : ''));
        ?>
      </div>
    </div>
    
    <div id="tc_queries">
      <div class="tc_query_box color_light_bg">
        <h3>
         <?php echo __('Queries', null, 'common') ?>
        </h3>
        <ul>
          <li><?php echo ull_link_to(__('All entries'), array('action' => 'list')) ?></li>
          <li><?php echo ull_link_to(__('Entries created by me'), array('action' => 'list', 'query' => 'by_me')) ?></li>
          <li><?php echo ull_link_to(__('Entries assigned to me'), array('action' => 'list', 'query' => 'to_me')) ?></li>
          <li><?php echo ull_link_to(__('Entries assigned to me or my groups'), array('action' => 'list', 'query' => 'to_me_and_my_groups')) ?></li>
        </ul>
      </div>
      <?php
        if (class_exists('UllFlowCustomQueries')):
        
      ?>
        <div class="tc_query_box color_light_bg">
        <h3>
          <?php echo __('Individual queries', null, 'common') ?>
        </h3>
        <ul>
         <?php 
            $u = new UllFlowCustomQueries;
            foreach ($u->getAllQueries() as $cqkey => $cqvalue)
            {
            	echo '<li>' . ull_link_to(__($cqkey), __($cqvalue)) . '</li>';
            }
            endif
         ?>
        </ul>
      </div>
    </div>
  </div>
  <div id="tc_footer">
     <!-- add footer here -->
  </div>
</div>