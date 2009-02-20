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
  
  <?php if (UllUserTable::hasGroup('MasterAdmins')): ?>
    <li id="nav_link_admin">
      <?php echo ull_navigation_link('/ullCoreThemeNGPlugin/images/ull_admin_32x32',
        'ullAdmin/index', __('Admin'),
        array()); 
      ?>
    </li>
  <?php endif ?>
  
</ul>