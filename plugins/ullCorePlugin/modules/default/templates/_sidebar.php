<?php echo __('Create', null, 'common') . ':' ?><br />
<?php
  foreach ($flowApps as $app)
  {
    echo ull_link_to($app->doc_label, 'ullFlow/create?app=' . $app->slug);
    echo '<br />';
  }
  echo '<br />';
  echo __('Links', null, 'common') . ':<br />';
  echo ull_link_to(__('My tasks', null, 'common'), 'ullFlow/list?query=to_me');
  echo '<br />';
?>