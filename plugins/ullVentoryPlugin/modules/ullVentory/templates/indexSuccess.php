<?php echo $sf_data->getRaw('breadcrumbTree')->getHtml();?>

<div id="tc_wrapper">
  <div id="tc_header">
    <!-- add header here -->
  </div>
  <div id="tc_container">
    <div id="tc_tasks">
      <h3><?php echo __('Actions', null, 'common') ?></h3>
      <ul class="tc_tasks">
        <li><?php echo ull_tc_task_link('/ullVentoryThemeNGPlugin/images/action_icons/create_24x24', 'ullVentory/create', __('Create', null, 'common')) ?></li>
      </ul>
    </div>
    
    <div id="tc_search">
      <div class="tc_search_quick_top color_medium_bg">
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
      <!-- 
      <div class="tc_search_quick_bottom color_light_bg"><br /><br /><br /><br />tba<br /></div>
      -->
      <div class="tc_search_tag_top color_medium_bg"><h3><?php echo __('By popular tags', null, 'common') ?></h3></div>
      <div class="tc_search_tag_bottom color_light_bg">
        &nbsp;   
      </div>
    </div>
    
    <div id="tc_queries">
      <div class="tc_query_box color_light_bg">
        <h3>
         <?php echo __('Queries', null, 'common') ?>
        </h3>
        <ul>
          <li><?php echo ull_link_to(__('Items changed today', null, 'common'), array('action' => 'list')) ?></li>
          <li><?php echo ull_link_to(__('PCs without software', null, 'common'), array('action' => 'list')) ?></li>
        </ul>
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