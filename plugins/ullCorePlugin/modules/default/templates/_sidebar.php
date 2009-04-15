<?php echo __('Create', null, 'common') . ':' ?><br />
<?php
  foreach ($flowApps as $app)
  {
    echo ull_link_to($app->doc_label, 'ullFlow/create?app=' . $app->slug, 'ull_js_observer_confirm=true');
    echo '<br />';
  }
  echo '<br />';
  echo __('Links', null, 'common') . ':<br />';
  echo ull_link_to(__('My personal tasks', null, 'common'), 'ullFlow/list?query=to_me', 'ull_js_observer_confirm=true') . '<br />';
  echo ull_link_to(__('All my tasks', null, 'common'), 'ullFlow/list?query=to_me_and_my_groups', 'ull_js_observer_confirm=true');
  echo '<br />';
?>