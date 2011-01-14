<ul class="nav_top_links">            

  <li id='nav_link_ull_mail'>
  <?php echo link_to(__('Newsletter', null, 'ullMailMessages'), 'ullNewsletter/index') ?>
  </li>
  
  <li id='nav_link_ull_ventory'>
    <?php echo link_to(__('Inventory', null, 'ullVentoryMessages'), 'ullVentory/index') ?>
  </li>
  
  <li id='nav_link_ull_phone'>
    <?php echo link_to(__('Phone directory', null, 'ullPhoneMessages'), 'ullPhone/list') ?>
  </li>
  
  <li id='nav_link_ull_orgchart'>
    <?php echo link_to(__('Orgchart', null, 'ullOrgchartMessages'), 'ullOrgchart/list') ?>
  </li>   

  <li id='nav_link_wiki'>
    <?php echo link_to(__('Wiki', null, 'common'), 'ullWiki/list') ?>
  </li>
  
  <li id='nav_link_ull_cms'>
  <?php echo link_to(__('Content', null, 'ullCmsMessages'), 'ullCms/list') ?>
  </li>     
   
  <li id='nav_link_workflow'>
    <?php echo link_to(__('Workflows', null, 'common'), 'ullFlow/index') ?>
  </li>
  
  <li id='nav_link_ull_time'>
    <?php echo link_to(__('Timereporting', null, 'ullTimeMessages'), 'ullTime/index') ?>
  </li>  
  
  <?php if (UllUserTable::hasGroup('MasterAdmins') || UllUserTable::hasGroup('UserAdmins')): ?>
    <li id="nav_link_admin">
      <?php echo link_to(__('Admin'), 'ullAdmin/index') ?>
    </li>
  <?php endif ?>
  
</ul>