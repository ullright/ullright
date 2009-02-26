
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
          $q = new Doctrine_Query;
//          $q->from('Tagging tg, tg.Tag t, tg.UllFlowDoc x');
          if ($app_slug) 
          {
            $q->where('tg.UllFlowDoc.ull_flow_app_id = ?', $app->id);            
          }
          
//          $q = UllFlowDocTable::queryAccess($q, $app_slug ? $sf_data->getRaw('app') : null);
          
//          $q->limit(sfConfig::get('app_sfDoctrineActAsTaggablePlugin_limit', 100));
          
//    var_dump($q->getQuery());
//    var_dump($q->getParams());
//    die('template');          
                    
          $tags_pop = TagTable::getPopulars($q, array('model' => 'UllFlowDoc', 'limit' => sfConfig::get('app_sfDoctrineActAsTaggablePlugin_limit', 100)));
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




<!-- 
<?php if ($app_slug): ?>
  <h1><?php echo __('Application') . ' ' . $app->label?></h1>
<?php else: ?>
  <h1><?php echo __('Workflows') . ' ' . __('Home') ?></h1>
  <h2><?php echo __('Applications') ?>:</h2>
  <ul>
  <?php foreach ($apps as $app): ?>
      <li>
    
  <?php echo link_to($app->label, 'ullFlow/index?app=' . $app->slug) ?></li>
  <?php endforeach; ?>
  </ul>  
<?php endif ?>

<h2><?php echo __('Search', null, 'common') ?>:</h2>
<?php echo ull_form_tag(array('action' => 'list'), array('class' => 'inline')); ?>
<ul>
  <li>
    <?php
      echo __('Quick search', null, 'common') . ': ';
      echo input_tag('filter[search]', null, array('size' => '30', 'onchange' => 'submit()', 'title' => __('Searches for ID, subject and tags', null, 'common')));
      echo ' ' . submit_tag(__('Search', null, 'common') . ' &gt;', 'title = ' . __('Searches for ID, subject and tags', null, 'common'));
    ?>
  </li>
</ul>

  
<?php if ($app_slug): ?>  
  <h2><?php echo __('Actions', null, 'common') ?>:</h2>
  <ul>
    <li>
      <?php 
        echo ull_link_to(__(
          'Create %1%', array('%1%' => $app->doc_label)),
          array('action' => 'create')
        );
      ?>
    </li>   
  </ul>
<?php endif ?>  
 

<h2><?php echo __('Queries', null, 'common') ?>:</h2>

<ul>
  <li><?php echo ull_link_to(__('All entries'), array('action' => 'list')) ?></li>
  <li><?php echo ull_link_to(__('Entries created by me'), array('action' => 'list', 'query' => 'by_me')) ?></li>
  <li><?php echo ull_link_to(__('Entries assigned to me'), array('action' => 'list', 'query' => 'to_me')) ?></li>
</ul>
 -->


  
<?php /*

  <li>
    <?php
      $c = new Criteria();
      if ($app_slug) {
        $c->addJoin(UllFlowDocPeer::ID, TaggingPeer::TAGGABLE_ID);
        $c->add(UllFlowDocPeer::ULL_FLOW_APP_ID, $app->getId());
      }
    
      $tags_pop = TagPeer::getPopulars($c, array('model' => 'UllFlowDoc'));
      sfLoader::loadHelpers(array('Tags'));
      echo __('By popular tags', null, 'common') . ':';
      echo tag_cloud($tags_pop, 'ullFlow/list?tags=%s' . $app_param);
      //, array('link_function' => 'link_to_function'));
      echo ull_js_add_tag();
      
    ?>
    
  </li>  
</ul>
</form>


*/ ?>
<!--  
<h2><?php echo __('Admin', null, 'common') ?>:</h2>
<ul>
  <li><?php echo link_to(__('Manage applications'), 'ullTableTool/list?table=UllFlowApp') ?></li>
  <li><?php echo link_to(__('Manage application access rights'), 'ullTableTool/list?table=UllFlowAppPermission') ?></li>
  <li><?php echo link_to(__('Manage columns'), 'ullTableTool/list?table=UllFlowColumnConfig') ?></li>
  <li><?php echo link_to(__('Manage steps'), 'ullTableTool/list?table=UllFlowStep') ?></li>
  <li><?php echo link_to(__('Manage actions for steps'), 'ullTableTool/list?table=UllFlowStepAction') ?></li>
  <li><?php echo link_to(__('Manage actions'), 'ullTableTool/list?table=UllFlowAction') ?></li>
</ul>
-->
