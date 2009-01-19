<ul class="nav_top_links">            
  
  <li id='nav_link_workflow'>
  <?php
    echo ull_navigation_link('/ullFlowThemeNGPlugin/images/ull_flow_32x32',
      'ullFlow/index', __('Workflows', null, 'common'));
  ?>
  </li>
  
  <li id='nav_link_tickets'>
  <?php
    echo ull_navigation_link('/ullFlowThemeNGPlugin/images/ull_flow_app_icons/trouble_ticket_32x32',
      'ullFlow/list?app=trouble_ticket', __('Active tickets', null, 'common'),
      array('ull_js_observer_confirm' => 'true'));
  ?>
  </li>
  
  <li id='nav_link_todo'>
  <?php
    echo ull_navigation_link('/ullFlowThemeNGPlugin/images/ull_flow_app_icons/todo_32x32',
      'ullFlow/list?query=to_me', __('My tasks', null, 'common'),
      array('ull_js_observer_confirm' => 'true'));
  ?>
  </li>
  
  <li id='nav_link_wiki'>
  <?php
  echo ull_navigation_link('/ullWikiThemeNGPlugin/images/ull_wiki_32x32',
      'ullWiki/list', __('Wiki', null, 'common'),
      array('ull_js_observer_confirm' => 'true'));
  ?>
  </li>
  
  <?php if (UllUserTable::hasGroup('MasterAdmins')): ?>
    <li id="nav_link_admin">
      <?php echo ull_navigation_link('/ullCoreThemeNGPlugin/images/ull_admin_32x32',
        'ullAdmin/index', __('Admin'),
        array('ull_js_observer_confirm' => 'true')); 
      ?>
    </li>
  <?php endif ?>
  
</ul>