<div class="sidebar_section" id="sidebar_ull_phone">

  <?php if (isset($locations)): ?>
    <h3><?php echo __('Phone book', null, 'ullPhoneMessages') ?></h3> 
    <ul class="sidebar_list">
      <li><?php echo ull_link_to(__('Everyone', null, 'ullPhoneMessages'), 'ullPhone/list', 'ull_js_observer_confirm=true') ?></li>
      <li><?php echo ull_link_to(__('Sort by location', null, 'ullPhoneMessages'), 'ullPhone/list?locationView=true', 'ull_js_observer_confirm=true') ?></li>
      <?php foreach ($locations as $location): ?>
        <li><?php echo ull_link_to($location['name'], 'ullPhone/list?locationView=true&ull_location_id=' . $location['id'], 'ull_js_observer_confirm=true') ?></li>
      <?php endforeach ?>
    </ul>

    <form action="<?php echo url_for('ullPhone/list?locationView=true') ?>" method="post">
      <?php echo $quickSearchForm['search']->render() ?>
      <?php echo submit_image_tag(ull_image_path('search', null, null, 'ullCore')) ?>
    </form>
    <?php echo javascript_tag('document.getElementById("filter_search").focus();'); ?>
  <?php endif ?>
  
  <?php   
    //uncomment this for autocompletion support
    //use_javascript('/sfFormExtraPlugin/js/jquery.autocompleter.js');
  ?>  
  
</div>


<?php
  // TODO: these module specific parts should be injected by the module.
?>
  
<div id="sidebar_default">

  <div class="sidebar_section" id="sidebar_create">
    <h3><?php echo __('Create', null, 'common') ?></h3>
    <ul class="sidebar_list">
    <?php foreach ($flowApps as $app): ?>
      <li><?php echo ull_link_to($app->doc_label, 'ullFlow/create?app=' . $app->slug, 'ull_js_observer_confirm=true') ?></li>
    <?php endforeach ?>
    </ul>
  </div>

  <div class="sidebar_section" id="sidebar_create">
    <h3><?php echo __('Links', null, 'common') ?></h3>
    <ul class="sidebar_list">
      <li><?php echo ull_link_to(__('My personal tasks', null, 'common'), 'ullFlow/list?query=to_me', 'ull_js_observer_confirm=true') ?></li>
      <li><?php echo ull_link_to(__('All my tasks', null, 'common'), 'ullFlow/list?query=to_me_or_my_groups', 'ull_js_observer_confirm=true') ?></li>
    </ul>
  </div>
  
</div>



