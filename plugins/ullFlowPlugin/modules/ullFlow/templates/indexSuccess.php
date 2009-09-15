
<?php echo $sf_data->getRaw('breadcrumbTree')->getHtml() ?>

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
          echo '<h3>' . __('Workflows', null, 'ullFlowMessages') . '</h3>';
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
      <div class="tc_box color_medium_bg">
        <?php echo ull_form_tag(array('action' => 'list')); ?>
          <table>
            <tr>
            <td><?php echo $form['search']->renderLabel() ?></td>    
            <td><?php echo $form['search']->render() ?></td>
            <td><?php echo submit_image_tag(ull_image_path('search'),
              array('class' => 'tc_search_quick_top_img')) ?></td>
              
          </tr>
          </table>
          </form>
         </div>
      
       
      <div class="tc_box_with_bottom_spacer color_light_bg">
           <?php
              if ($app_slug)
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
        <?php echo $namedQueries->renderList(ESC_RAW) ?>
      </div>
      <?php
        if (class_exists('ullNamedQueriesUllFlowCustom')):  
      ?>
        <div class="tc_query_box color_light_bg">
        <h3>
          <?php echo __('Individual queries', null, 'common') ?>
        </h3>
        <?php echo $namedQueriesCustom->renderList(ESC_RAW) ?>
      </div>
      <?php endif ?>
    </div>
  </div>
  <div id="tc_footer">
     <!-- add footer here -->
  </div>
</div>