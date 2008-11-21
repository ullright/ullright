
<?php echo $sf_data->getRaw('breadcrumbTree')->getHtml() ?>

<?php if ($app_slug): ?>
  <h1><?php echo __('Application') . ' ' . $app->label?></h1>
<?php else: ?>
  <h1><?php echo __('Workflows') . ' ' . __('Home') ?></h1>
  <h2><?php echo __('Applications') ?>:</h2>
  <ul>
  <?php foreach ($apps as $app): ?>
      <li><?php echo link_to($app->label, 'ullFlow/index?app=' . $app->slug) ?></li>
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

<?php
  if (class_exists('UllFlowCustomQueries')) {
    $u = new UllFlowCustomQueries();
    echo $u->listQueries();
  }
?>

  
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




<h2><?php echo __('Admin', null, 'common') ?>:</h2>
<ul>
  <li><?php echo link_to(__('Rebuild doc access rights'), 'ullFlow/rebuildDocAccess') ?></li>
</ul>

*/ ?>