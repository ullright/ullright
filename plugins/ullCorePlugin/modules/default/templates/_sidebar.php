<?php
  //uncomment this for autocompletion support
  //use_javascript('/sfFormExtraPlugin/js/jquery.autocompleter.js');

  // TODO: these module specific parts should be injected by the module.
  // TODO: use <ul> instead of <br>

  echo __('Create', null, 'common') . ':';
  echo '<br />';

  foreach ($flowApps as $app)
  {
    echo ull_link_to($app->doc_label, 'ullFlow/create?app=' . $app->slug, 'ull_js_observer_confirm=true');
    echo '<br />';
  }
  echo '<br />';

  echo __('Links', null, 'common') . ':<br />';
  echo ull_link_to(__('My personal tasks', null, 'common'), 'ullFlow/list?query=to_me', 'ull_js_observer_confirm=true') . '<br />';
  echo ull_link_to(__('All my tasks', null, 'common'), 'ullFlow/list?query=to_me_or_my_groups', 'ull_js_observer_confirm=true');
  echo '<br />';

  if (isset($locations))
  {
    echo '<br />';
    echo __('Phone book', null, 'ullPhoneMessages') . ':';
    echo '<br />';
    echo ull_link_to(__('Everyone', null, 'ullPhoneMessages'), 'ullPhone/list', 'ull_js_observer_confirm=true');
    echo '<br />';
    echo ull_link_to(__('Sort by location', null, 'ullPhoneMessages'), 'ullPhone/list?locationView=true', 'ull_js_observer_confirm=true');
    echo '<br />';
    foreach ($locations as $location)
    {
      echo ull_link_to($location['name'], 'ullPhone/list?locationView=true&ull_location_id=' . $location['id'], 'ull_js_observer_confirm=true');
      echo '<br />';
    }
    echo '<br />';
  }
?>

<?php if (isset($quickSearchForm)): ?>
  <form action="<?php echo url_for('ullPhone/list?locationView=true') ?>" method="post">
  <?php echo $quickSearchForm['search']->render() ?>
  <?php echo submit_image_tag(ull_image_path('search', null, null, 'ullCore')) ?>
  </form>  
<?php endif ?>


