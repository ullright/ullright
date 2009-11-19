<ul class="nav_top_links">            
  
  <li id='nav_link_workflow'>
  <?php
    echo ull_navigation_link('/ullFlowThemeNGPlugin/images/ull_flow_32x32',
      'ullFlow/index', __('Workflows', null, 'common'));
  ?>
  </li>
  
  <li id='nav_link_wiki'>
  <?php
  echo ull_navigation_link('/ullWikiThemeNGPlugin/images/ull_wiki_32x32',
      'ullWiki/list', __('Wiki', null, 'common'),
      array());
  ?>
  </li>
  
  <li id='nav_link_ull_ventory'>
  <?php
  echo ull_navigation_link('/ullVentoryThemeNGPlugin/images/ull_ventory_32x32',
      'ullVentory/index', __('Inventory', null, 'ullVentoryMessages'),
      array());
  ?>
  </li>
  
  <li id='nav_link_ull_time'>
  <?php
  echo ull_navigation_link('/ullTimeThemeNGPlugin/images/ull_time_32x32',
      'ullTime/index', __('Timereporting', null, 'ullTimeMessages'),
      array());
  ?>
  </li>  
  
  <li id='nav_link_ull_phone'>
  <?php
  echo ull_navigation_link('/ullPhoneThemeNGPlugin/images/ull_phone_32x32',
      'ullPhone/list', __('Phone directory', null, 'ullPhoneMessages'),
      array());
  ?>
  </li>  
  
  <?php if (UllUserTable::hasGroup('MasterAdmins')): ?>
    <li id="nav_link_admin">
      <?php echo ull_navigation_link('/ullCoreThemeNGPlugin/images/ull_admin_32x32',
        'ullAdmin/index', __('Admin'),
        array()); 
      ?>
    </li>
  <?php endif ?>
  
</ul>